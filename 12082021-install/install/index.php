<?php

 /**
  * Autor: Lizbeth Johana Caro Suarez
  */

  //este es el index.
if($conexion == "../document/DB/traductor_nativo.sql")
{
	echo "la base de datos ha sido creada";
}else{
	
	if( file_exists( "../install/www/instalador.php" ) == true )
	{
        //echo "hola mundo";
		//echo "El archivo de configuración existe, se procederá a ir al sitio.";
		header( "location: ../install/www/instalador.php" );
	
	}else{
			//echo "El archivo no existe, se proceder&aacute; a ir al instalador.";
			header( "location: ../install/document/index.php" );
		}
}

    
    
    
