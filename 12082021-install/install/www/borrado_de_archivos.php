<?php
/**
 * Autor: Lizbeth Johana Caro Suarez
 */
//este es el borrado de archivos

include( "verificador.php" );
    $objeto_verificador = new Verificador();

    $objeto_verificador->borrar_archivo( "instalador.php" );
    $objeto_verificador->borrar_archivo( "instalando.php" );
    header( "location: menu.php" );