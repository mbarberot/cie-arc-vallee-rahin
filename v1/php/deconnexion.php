<?php
include 'fonctions_spec.php' ;
session_start();
unset($_SESSION['user']);
unset($_SESSION['connected']);
unset($_SESSION['cat']);
cie_redirect('../');
?>