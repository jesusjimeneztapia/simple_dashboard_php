<?php
use SimpleDashboardPHP\Core\View;

$maxButtons = 5;

$pagination = $projects["pagination"];
$page = $pagination["page"];
$offset = $pagination["offset"];
$total_items = $pagination["total_items"];
$total_pages = $pagination["total_pages"];
$prev_page = $pagination["prev_page"];
$next_page = $pagination["next_page"];
$has_prev = $pagination["has_prev"];
$has_next = $pagination["has_next"];

$middle = floor($maxButtons / 2);
$start = max(1, $page - $middle);
$end = min($total_pages, $start + $maxButtons - 1);

if (($end - $start + 1) < $maxButtons) {
  $start = max(1, $end - $maxButtons + 1);
}

function renderizarPaginacion($paginaActual, $totalPaginas, $maxBotones = 5) {
  if ($totalPaginas <= 1) return; // No mostrar si solo hay una página

  echo '<ul class="pagination float-right">';

  // Botón "Anterior"
  if ($paginaActual > 1) {
    echo '
      <li class="paginate_button page-item previous" id="pagination_previous">
        <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">
          Previous
        </a>
      </li>
    ';
    // echo '<a href="?pagina=' . ($paginaActual - 1) . '" class="btn">Anterior</a>';
  } else {
    echo '
      <li class="paginate_button page-item previous disabled" id="pagination_previous">
        <span aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">
          Previous
        </span>
      </li>
    ';
    // echo '<span class="btn deshabilitado">Anterior</span>';
  }

  // Calcular rango dinámico
  $mitad = floor($maxBotones / 2);
  $inicio = max(1, $paginaActual - $mitad);
  $fin = min($totalPaginas, $inicio + $maxBotones - 1);

  // Ajuste si estás al final (para que siempre se muestren $maxBotones botones si es posible)
  if (($fin - $inicio + 1) < $maxBotones) {
    $inicio = max(1, $fin - $maxBotones + 1);
  }

  // Mostrar "1 ..." si no empieza en 1
  if ($inicio > 1) {
    echo '
      <li class="paginate_button page-item">
        <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">1</a>
      </li>
    ';
    // echo '<a href="?pagina=1" class="btn">1</a>';
    if ($inicio > 2) {
      echo '
        <li class="paginate_button page-item">
          <span class="page-link">...</span>
        </li>
      ';
      // echo '<span class="puntos">...</span>';
    }
  }

  // Mostrar rango de páginas
  for ($i = $inicio; $i <= $fin; $i++) {
    if ($i == $paginaActual) {
      echo "
        <li class='paginate_button page-item active'>
          <a href='#' aria-controls='example2' data-dt-idx='$i' tabindex='0' class='page-link'>$i</a>
        </li>
      ";
      // echo '<span class="btn actual">' . $i . '</span>';
    } else {
      echo "
        <li class='paginate_button page-item'>
          <a href='#' aria-controls='example2' data-dt-idx='$i' tabindex='0' class='page-link'>$i</a>
        </li>
      ";
      // echo '<a href="?pagina=' . $i . '" class="btn">' . $i . '</a>';
    }
  }

  // Mostrar "... última" si no termina en la última
  if ($fin < $totalPaginas) {
    if ($fin < $totalPaginas - 1) {
      echo '
        <li class="paginate_button page-item">
          <span class="page-link">...</span>
        </li>
      ';
      // echo '<span class="puntos">...</span>';
    }
    echo "
      <li class='paginate_button page-item'>
        <a href='#' aria-controls='example2' data-dt-idx='$totalPaginas' tabindex='0' class='page-link'>$totalPaginas</a>
      </li>
    ";
    // echo '<a href="?pagina=' . $totalPaginas . '" class="btn">' . $totalPaginas . '</a>';
  }

  // Botón "Siguiente"
  if ($paginaActual < $totalPaginas) {
    echo "
      <li class='paginate_button page-item previous' id='pagination_previous'>
        <a href='#' aria-controls='example2' data-dt-idx='".($paginaActual + 1)."' tabindex='0' class='page-link'>
          Next
        </a>
      </li>
    ";
    // echo '<a href="?pagina=' . ($paginaActual + 1) . '" class="btn">Siguiente</a>';
  } else {
    echo "
      <li class='paginate_button page-item previous disabled' id='pagination_previous'>
        <span class='page-link'>
          Next
        </span>
      </li>
    ";
    // echo '<span class="btn deshabilitado">Siguiente</span>';
  }

  echo '</ul>';
}

?>
<!-- Default box -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Projects</h3>

    <div class="card-tools">
      <button
        type="button"
        class="btn btn-tool"
        data-card-widget="collapse"
        title="Collapse"
      >
        <i class="fas fa-minus"></i>
      </button>
      <button
        type="button"
        class="btn btn-tool"
        data-card-widget="remove"
        title="Remove"
      >
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="card-body p-0" style="display: block">
    <?= View::render("projects/components/ProjectsTable", ["projects" => $projects["data"]]) ?>
  </div>
  <!-- /.card-body -->
  <div class="card-footer clearfix">
    <div class="row">
      <div class="col-sm-12 col-md-5">
        <div id="example2_info" role="status" aria-live="polite">
          Showing <?= $offset + 1 ?> to <?= $offset + sizeof($projects["data"]) ?> of <?= $total_items ?> entries
        </div>
      </div>

      <div class="col-sm-12 col-md-7">
        <div id="example2_paginate">
          <?php renderizarPaginacion($page, $total_pages, 6) ?>
          <!-- <ul class="pagination float-right">
            <li class="paginate_button page-item previous disabled" id="example2_previous">
              <a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">
                Previous
              </a>
            </li>
            <li class="paginate_button page-item active">
              <a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">1</a>
            </li>
            <li class="paginate_button page-item ">
              <a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0" class="page-link">2</a>
            </li>
            <li class="paginate_button page-item ">
              <a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0" class="page-link">3</a>
            </li>
            <li class="paginate_button page-item ">
              <a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0" class="page-link">4</a>
            </li>
            <li class="paginate_button page-item ">
              <a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a>
            </li>
            <li class="paginate_button page-item ">
              <a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0" class="page-link">6</a>
            </li>
            <li class="paginate_button page-item next" id="example2_next">
              <a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">
                Next
              </a>
            </li>
          </ul> -->
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.paginacion {
    display: flex;
    gap: 4px;
    margin: 20px 0;
}
.paginacion .btn {
    padding: 6px 10px;
    border: 1px solid #ccc;
    text-decoration: none;
    color: #333;
    border-radius: 4px;
}
.paginacion .actual {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
.paginacion .deshabilitado {
    color: #aaa;
    border-color: #eee;
    pointer-events: none;
}
.paginacion .puntos {
    padding: 6px 8px;
    color: #666;
}
</style>
<!-- /.card -->
