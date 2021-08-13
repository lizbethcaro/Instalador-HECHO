<?php
/**
 * Autor: Lizbeth johana caro suarez.
 * 
 * Este es el instalador completarado
 */

$sql = file_get_contents( "../document/DB/base_de_datos.sql" );

$host = $_GET['servidor'];
$usuario = $_GET['usuario'];
$contrasena = $_GET['contrasena'];
$nombre_de_base= $_GET['bd'];


//Borrado de variables inecesarios de la base de datos en las funciones 
$sql = str_replace("DELIMITER",'', $sql );
$sql = str_replace("$$",'', $sql );
$sql = str_replace("//",'', $sql );

    $conexion = @mysqli_connect( $host, $usuario, $contrasena, $nombre_de_base );
    //$conexion->multi_query($sql);
   
    if(!$conexion)
    {
        echo $error = mysqli_connect_error();  
        echo "conexion eronbea";
    }else{
        
        if($conexion->multi_query($sql))
        {
            echo "<h1>LA BASE DE DATOS HA SIDO CREADA</h1>";
        }else{
            echo "Error: ". $conexion->error;
        }
       
        //$sql = file_get_contents( "../document/DB/traductor_nativo.sql" );
    }
    
 
    
    echo str_replace("\n", "<br>", $sql );

?>