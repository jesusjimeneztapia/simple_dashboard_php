<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>
    AdminLTE 3<?= isset($head["title"]) ? " | ".$head["title"] : "" ?>
  </title>

  <?php if (!empty($head["meta"])): ?>
    <?php foreach ($head["meta"] as $meta): ?>
      <meta name="<?= $meta["name"] ?? "" ?>" content="<?= $meta["content"] ?? "" ?>">
    <?php endforeach ?>
  <?php endif ?>

  <!-- Google Font: Source Sans Pro -->
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
  />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="
  <?= asset("plugins/fontawesome-free/css/all.min.css") ?>
  " />
  <!-- Ionicons -->
  <link
    rel="stylesheet"
    href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"
  />

  <?php if (!empty($head["link"])): ?>
    <?php foreach ($head["link"] as $link): ?>
      <link rel="<?= $link["rel"] ?? "" ?>" href="<?= $link["href"] ?? "" ?>">
    <?php endforeach ?>
  <?php endif ?>

  <!-- Tempusdominus Bootstrap 4 -->
  <link
    rel="stylesheet"
    href="<?= asset("plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") ?>"
  />
  <!-- iCheck -->
  <link
    rel="stylesheet"
    href="<?= asset("plugins/icheck-bootstrap/icheck-bootstrap.min.css") ?>"
  />
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?= asset("plugins/jqvmap/jqvmap.min.css") ?>" />
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= asset("dist/css/adminlte.min.css?v=3.2.0") ?>" />
  <!-- overlayScrollbars -->
  <link
    rel="stylesheet"
    href="<?= asset("plugins/overlayScrollbars/css/OverlayScrollbars.min.css") ?>"
  />
  <!-- Daterange picker -->
  <link
    rel="stylesheet"
    href="<?= asset("plugins/daterangepicker/daterangepicker.css") ?>"
  />
  <!-- summernote -->
  <link rel="stylesheet" href="<?= asset("plugins/summernote/summernote-bs4.min.css") ?>" />

  <?php if (!empty($head["assetLink"])): ?>
    <?php foreach ($head["assetLink"] as $link): ?>
      <link rel="<?= $link["rel"] ?? "" ?>" href="<?= asset($link["href"]) ?>">
    <?php endforeach ?>
  <?php endif ?>

  <?php if (!empty($head["script"])): ?>
    <?php foreach ($head["script"] as $script): ?>
      <script
        <?= $script["type"] ? "type='" . $script["type"] . "'" : "" ?>
        <?= $script["src"] ? "src='" . $script["src"] . "'" : "" ?>
      ></script>
    <?php endforeach ?>
  <?php endif ?>

  <?php if (!empty($head["assetScript"])): ?>
    <?php foreach ($head["assetScript"] as $script): ?>
      <script
        <?= $script["type"] ? "type='" . $script["type"] . "'" : "" ?>
        <?= $script["src"] ? "src='" . asset($script["src"]) . "'" : "" ?>
      ></script>
    <?php endforeach ?>
  <?php endif ?>
</head>