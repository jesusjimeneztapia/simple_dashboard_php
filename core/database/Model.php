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