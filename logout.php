<?php declare(strict_types=1); // włączenie typowania zmiennych w PHP >=7
session_start(); 
session_unset();
header('Location: index3.php');
?>