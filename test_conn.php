<?php
 echo "hi world";
 $connection = mysqli_connect('127.0.0.1', 'root', '','my_db');
 if( $connection == false )
 {
    echo 'neud';
    echo mysqli_connect_error();
    exit();
 } else 
 {
    echo 'molodec';
 }
?>