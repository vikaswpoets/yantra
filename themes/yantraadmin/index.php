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
    <!-- Title -->
    <title><?php echo $title ?? 'Kaveri'; ?></title>
<!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">-->
    <link rel="stylesheet" href="<?= theme_url(); ?>/style.css">
    <link rel="stylesheet" href="<?= theme_url(); ?>/layout.css">
    <link rel="stylesheet" href="<?= theme_url(); ?>/elements.css">
    <link rel="stylesheet" href="<?= theme_url(); ?>/utility.css">
    <link rel="stylesheet" href="<?= theme_url(); ?>/sidebar.css">
    <?php do_action('site-header'); ?>
</head>
<body class="<?= $body_class??''; ?>">
 <?php
 do_action('page-top');
 //the_section('parts','page-top-bar');
 ?>
 <main>
     <?php
     do_block('sidebar-nav',"");
     ?>
     <div class="content" id="content">
         <?= $content; ?>
         <?php /*do_block('content',""); */?>
         <?php do_block('footer',""); ?>
     </div>
 </main>
 <?php
 the_section('','scripts' );
 do_action('footer-scripts');
 ?>
</body>
</html>