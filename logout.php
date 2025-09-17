<?php
// logout.php
session_start();
session_unset();
session_destroy();
header("Location: /cafora_coffee_php/index.php");
exit;
?>
