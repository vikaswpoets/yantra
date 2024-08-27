<?php
use System\Theme;
global $router, $request, $response, $env;
require_once 'functions.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?? 'Kaveri'; ?></title>
    <link rel="stylesheet" href="<?= theme_url(); ?>/style.css">
    <?php do_action('site-header'); ?>
</head>
<body class="<?= $body_class??''; ?>">
 <?php
 do_action('page-top');
 ?>
 <div class="main-content- h-100vh">
     <?php do_block('content',""); ?>
 </div>
<?php
 the_section('','scripts' );
 do_action('footer-scripts');
 ?>
</body>
</html>