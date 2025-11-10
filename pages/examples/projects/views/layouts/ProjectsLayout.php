<?php
use Projects\Core\View;

?>
<!DOCTYPE html>
<html lang="en">
  <?= View::render("layouts/components/ProjectsLayoutHead", ["head" => $head ?? []]) ?>

  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <?= View::render("layouts/components/ProjectsLayoutNavbar") ?>
      <?= View::render("layouts/components/ProjectsLayoutSidebar", ["sidebar" => $sidebar ?? []]) ?>
      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php if(isset($head["title"])): ?>
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1><?= $head["title"] ?></h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active"><?= $head["title"] ?></li>
                  </ol>
                </div>
              </div>
            </div>
            <!-- /.container-fluid -->
          </section>
        <?php endif ?>
        
        <section class="content">
          <?= $body["content_html"] ?? "" ?>
        </section>
      </div>
      <!-- /.content-wrapper -->
  
      <?= View::render("layouts/components/ProjectsLayoutFooter") ?>
    </div>
    
    <?= View::render("layouts/components/ProjectsLayoutScript") ?>
  </body>
</html>