<?php
namespace SimpleDashboardPHP\Core\Database;

abstract class Model
{
  protected static ?\mysqli $connection = null;

  protected string $table = "";
  protected array $fillable = [];
  protected array $attributes = [];
  public ?int $id = null;

  public function __construct(array $data = [])
  {
    foreach ($data as $key => $value) {
      if (in_array($key, $this->fillable)) {
        $this->attributes[$key] = $value;
      }
    }

    if (isset($data['id'])) {
      $this->id = $data['id'];
    }
  }

  public static function setConnection(\mysqli $connection): void
  {
    self::$connection = $connection;
  }

  protected static function getConnection(): \mysqli
  {
    if (!self::$connection) {
      throw new \Exception("Database connection not set. Call Model::setConnection(\$connection) first.");
    }
    return self::$connection;
  }

  public static function all() {
    $instance = new static();

    $conn = self::getConnection();
    $query = "SELECT * FROM " . $instance->table;

    $result = $conn->query($query);

    if (!$result) {
      throw new \Exception("Query failed: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
  }

  // 🔸 Paginación extendida
  public static function paginate(
    int $page = 1,
    int $per_page = 10,
    array $filters = [],
    $order_by = null,
    $select = '*',
    array $joins = [],
    array $group_by = []
  ): array {
    $instance = new static();
    $conn = self::getConnection();
    $offset = ($page - 1) * $per_page;

    // --- SELECT ---
    if (is_array($select)) {
      $select = implode(', ', array_map(fn($col) => "`$col`", $select));
    }

    $query = "SELECT $select FROM `{$instance->table}`";

    // --- JOINs ---
    if (!empty($joins)) {
      foreach ($joins as $join) {
        // Formato: ['LEFT', 'posts p', 'p.user_id = u.id']
        [$type, $table, $on] = $join;
        $type = strtoupper($type);
        if (!in_array($type, ['LEFT', 'RIGHT', 'INNER'])) continue;
        $query .= " $type JOIN $table ON $on";
      }
    }

    // --- WHERE dinámico ---
    $whereParts = [];
    $types = '';
    $values = [];

    foreach ($filters as $key => $value) {
      $operator = '=';
      $column = $key;

      if (preg_match('/\s+(=|>|<|>=|<=|!=|LIKE|IN|BETWEEN)$/i', $key, $matches)) {
        $operator = strtoupper(trim($matches[1]));
        $column = trim(str_replace($matches[0], '', $key));
      }

      switch ($operator) {
        case 'IN':
          if (!is_array($value) || empty($value)) break;
          $placeholders = implode(',', array_fill(0, count($value), '?'));
          $whereParts[] = "$column IN ($placeholders)";
          foreach ($value as $v) {
            $types .= self::getBindType($v);
            $values[] = $v;
          }
          break;

        case 'BETWEEN':
          if (!is_array($value) || count($value) !== 2) break;
          $whereParts[] = "$column BETWEEN ? AND ?";
          $types .= self::getBindType($value[0]) . self::getBindType($value[1]);
          $values[] = $value[0];
          $values[] = $value[1];
          break;

        default:
          $whereParts[] = "$column $operator ?";
          $types .= self::getBindType($value);
          $values[] = $value;
          break;
      }
    }

    $whereClause = '';
    if (!empty($whereParts)) {
      $whereClause = " WHERE " . implode(' AND ', $whereParts);
    }

    // --- GROUP BY ---
    $groupClause = '';
    if (!empty($group_by)) {
      $groupClause = " GROUP BY " . implode(', ', array_map(fn($col) => $col, $group_by));
    }

    // --- ORDER BY ---
    $orderClause = '';
    if (!empty($order_by)) {
      if (is_array($order_by)) {
        $parts = [];
        foreach ($order_by as $col => $dir) {
          $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
          $parts[] = "$col $dir";
        }
        $orderClause = " ORDER BY " . implode(', ', $parts);
      } elseif (is_string($order_by)) {
        $orderClause = " ORDER BY $order_by ASC";
      }
    }

    // --- LIMIT / OFFSET ---
    $query .= $whereClause . $groupClause . $orderClause . " LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) die("Error to prepare: " . $conn->error);

    $types .= 'ii';
    $values[] = $per_page;
    $values[] = $offset;

    $stmt->bind_param($types, ...self::refValues($values));
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // --- Total ---
    $count_query = "SELECT COUNT(*) as total FROM `{$instance->table}`";
    if (!empty($joins)) {
      foreach ($joins as $join) {
        [$type, $table, $on] = $join;
        $type = strtoupper($type);
        $count_query .= " $type JOIN $table ON $on";
      }
    }
    $count_query .= $whereClause . $groupClause;

    $count_stmt = $conn->prepare($count_query);
    if (!$count_stmt) die("Error to prepare count: " . $conn->error);

    if (!empty($whereParts)) {
      $count_types = substr($types, 0, -2);
      $count_values = array_slice($values, 0, -2);
      $count_stmt->bind_param($count_types, ...self::refValues($count_values));
    }

    $count_stmt->execute();
    $total = (int)$count_stmt->get_result()->fetch_assoc()['total'];

    $stmt->close();
    $count_stmt->close();
    $conn->close();

    // --- Resultado final ---
    $has_next = $page * $per_page < $total;
    $has_prev = $page > 1;
    return [
      'data' => $data,
      'pagination' => [
        'page' => $page,
        'per_page' => $per_page,
        'offset' => $offset,
        'total_items' => $total,
        'total_pages' => ceil($total / $per_page),
        'has_next' => $has_next,
        'has_prev' => $has_prev,
        'next_page' => $has_next ? $page + 1 : null,
        'prev_page' => $has_prev ? $page - 1 : null,
      ]
    ];
  }

  // public static function paginate(
  //   int $page = 1,
  //   int $per_page = 10,
  //   array $filters = [],
  //   $order_by = null,
  //   $select = "*"
  // ) {
  //   $instance = new static();
  //   $conn = self::getConnection();
  //   $offset = ($page - 1) * $per_page;

  //   // --- SELECT ---
  //   if (is_array($select)) {
  //     $select = implode(', ', array_map(fn($col) => "`$col`", $select));
  //   }

  //   $query = "SELECT $select FROM `{$instance->table}`";

  //   // --- WHERE ---
  //   $where = '';
  //   $types = '';
  //   $values = [];
  //   if (!empty($filters)) {
  //     $conditions = [];
  //     foreach ($filters as $column => $value) {
  //       $conditions[] = "`$column` = ?";
  //       $types .= self::getBindType($value);
  //       $values[] = $value;
  //     }
  //     $where = " WHERE " . implode(" AND ", $conditions);
  //   }

  //   // --- ORDER BY ---
  //   $order_clause = '';
  //   if (!empty($order_by)) {
  //     if (is_array($order_by)) {
  //       $parts = [];
  //       foreach ($order_by as $col => $dir) {
  //         $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
  //         $parts[] = "`$col` $dir";
  //       }
  //       $order_clause = " ORDER BY " . implode(', ', $parts);
  //     } elseif (is_string($order_by)) {
  //       $order_clause = " ORDER BY `$order_by` ASC";
  //     }
  //   }

  //   // --- LIMIT & OFFSET ---
  //   $query .= $where . $order_clause . " LIMIT ? OFFSET ?";

  //   $stmt = $conn->prepare($query);

  //   if (!$stmt) {
  //       die("Error to prepare: " . $conn->error);
  //   }

  //   // Agregar tipos y valores
  //   $types .= 'ii'; // para LIMIT y OFFSET
  //   $values[] = $per_page;
  //   $values[] = $offset;

  //   // bind_param requiere referencias
  //   $stmt->bind_param($types, ...self::refValues($values));
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   $data = $result->fetch_all(MYSQLI_ASSOC);

  //   // --- TOTAL ---
  //   $count_query = "SELECT COUNT(*) as total FROM `{$instance->table}`" . $where;
  //   $count_stmt = $conn->prepare($count_query);
  //   if ($where !== '') {
  //     $count_stmt->bind_param(substr($types, 0, strlen($types) - 2), ...self::refValues(array_slice($values, 0, -2)));
  //   }
  //   $count_stmt->execute();
  //   $count_result = $count_stmt->get_result();
  //   $total = (int)$count_result->fetch_assoc()['total'];

  //   $stmt->close();
  //   $count_stmt->close();
  //   $conn->close();

  //   // --- Estructura de salida ---
  //   $has_next = $page * $per_page < $total;
  //   $has_prev = $page > 1;
  //   return [
  //     'data' => $data,
  //     'pagination' => [
  //       'page' => $page,
  //       'per_page' => $per_page,
  //       'total_items' => $total,
  //       'total_pages' => ceil($total / $per_page),
  //       'has_next' => $has_next,
  //       'has_prev' => $has_prev,
  //       'next_page' => $has_next ? $page + 1 : null,
  //       'prev_page' => $has_prev ? $page - 1 : null,
  //     ]
  //   ];
  // }

  // 🔸 Detecta tipo para bind_param
  protected static function getBindType($value): string {
    switch (gettype($value)) {
      case 'integer': return 'i';
      case 'double':  return 'd';
      default:        return 's';
    }
  }

  // 🔸 MySQLi requiere referencias al hacer bind_param con array dinámico
  protected static function refValues(array $arr): array {
    $refs = [];
    foreach ($arr as $key => $value) {
      $refs[$key] = &$arr[$key];
    }
    return $refs;
  }

  public static function page(int $page = 1, int $per_page = 15) {
    $instance = new static();

    $offset = ($page - 1) * $per_page;

    $conn = self::getConnection();
    $query = "SELECT * FROM `$instance->table` LIMIT $per_page OFFSET $offset";

    $result = $conn->query($query);

    if (!$result) {
      throw new \Exception("Query failed: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
  }

  /**
   * Busca un registro por su ID.
   *
   * @param int $id
   * @return static|null
   */
  public static function find(int $id)
  {
    $instance = new static();
    $connection = self::getConnection();

    $table = $instance->table;
    $sql = "SELECT * FROM `$table` WHERE id = $id LIMIT 1";

    $result = $connection->query($sql);

    if (!$result) {
      throw new \Exception("Query failed: " . $connection->error);
    }

    $data = $result->fetch_assoc();
    if (!$data) {
      return null;
    }

    $model = new static($data);

    return $model;
  }

  public function __set($name, $value)
  {
    if (in_array($name, $this->fillable)) {
      $this->attributes[$name] = $value;
    }
  }

  public function __get($name)
  {
    return $this->attributes[$name] ?? null;
  }

  public function save()
  {
    if (empty($this->attributes)) {
      throw new \Exception("No attributes to insert.");
    }

    $connection = self::getConnection();

    if ($this->id) {
      $sets = [];
      foreach ($this->attributes as $key => $value) {
        $escaped = mysqli_real_escape_string($connection, $value);
        $sets[] = "`$key`='$escaped'";
      }
      $sql = "UPDATE `$this->table` SET " . implode(',', $sets) . " WHERE id=$this->id";
      if (!mysqli_query($connection, $sql)) {
        throw new \Exception("Update failed: " . mysqli_error($connection));
      }
    } else {
      $columns = implode(',', array_keys($this->attributes));
      $values = implode(',', array_map(fn($v) => "'" . mysqli_real_escape_string($connection, $v) . "'", $this->attributes));
      $sql = "INSERT INTO `$this->table` ($columns) VALUES ($values)";
      if (!mysqli_query($connection, $sql)) {
        throw new \Exception("Insert failed: " . mysqli_error($connection));
      }
      $this->id = mysqli_insert_id($connection);
    }

    return $this->id;
  }

  public function update(array $data = [])
  {
    foreach ($data as $key => $value) {
      if (in_array($key, $this->fillable)) {
        $this->attributes[$key] = $value;
      }
    }

    $this->save();
  }

  public function delete() {
    if (!isset($this->id)) {
      throw new \Exception("No id to delete.");
    }

    $connection = self::getConnection();

    $sql = "DELETE FROM `$this->table` WHERE id=" . $this->id;
    var_dump($sql);
    if (!mysqli_query($connection, $sql)) {
      throw new \Exception("Delete failed: " . mysqli_error($connection));
    }

    return $this->id;
  }

  public function getAttributes() {
    return $this->attributes + ["id" => $this->id];
  }
}