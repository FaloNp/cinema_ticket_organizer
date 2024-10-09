<?php
session_start();
$_SESSION = array();
unset($_SESSION);
session_unset();
header("Location: index.php");
