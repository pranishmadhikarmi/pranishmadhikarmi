<?php

$con=mysqli_connect("localhost","root","","shoes_store");

if(mysqli_connect_error()){
    echo"<script> alert('Cannot connect to the database');</script>";
    exit();
}


?>

