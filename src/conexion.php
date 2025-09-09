<?php
include(__DIR__ . "/configuracion.php");
$conexion = new mysqli($server,$user,$password,$bd);
if(mysqli_connect_errno()){
    exit();
}
?>