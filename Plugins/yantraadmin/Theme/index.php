<?php
use System\Theme;
extract($data);
require_once 'functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php Theme::get_header('',$data); ?>
</head>
<body>
<?php Theme::get_content($view,$data); ?>
<?php Theme::get_footer('',$data); ?>
</body>
</html>