<?php
require_once __DIR__ . '/../lib/authen_lib.php';
deconnecterUtilisateur();
header('Location: index.php?page=login');
exit;
?>

