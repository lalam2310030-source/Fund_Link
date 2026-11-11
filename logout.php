<?php
session_start();
session_unset();
session_destroy();
header("Location: Login_&_Register/index.php");
exit();
?>
