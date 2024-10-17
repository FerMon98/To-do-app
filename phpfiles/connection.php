<?php
    //Codigo para guardar los datos de la base de datos
    $host = "localhost";
    $port = "3306";
    $database = "gestor_tarea_fer";


    $user = "root";
    $password = "";


    $link = "mysql:host=$host;dbname=$database;charset=utf8mb4";


    //Procedimiento para conectar php con my sql


    try {
        $conn = new PDO($link, $user, $password); //Metodo para connectar php con my sql


    } catch (PDOException $e){
       
        //mensaje por si aparece un error
        print "Error!".$e ->getMessage()."br";
        die();
    }


?>
