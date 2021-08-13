<?php

 /**
  * Autor: Lizbeth Johana Caro Suarez
  */

  //este es el index.

    if( file_exists( "instalador.php" ) == true )
	{
        //echo "hola mundo";
		//echo "El archivo de configuración existe, se procederá a ir al sitio.";
		header( "location: instalador.php" );
	
	}else{
			//echo "El archivo no existe, se proceder&aacute; a ir al instalador.";
			header( "location: menu.php" );
		}