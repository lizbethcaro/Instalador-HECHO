<?php

/**
 * Autor: Lizbeth Johana Caro Suarez
 * 
 * Nota: este sera el codigo donde se creara a la instalacion.
 */

 include "../www/verificador.php";
 $objeto_verificador = new Verificador();
 define( "NUMERO_DE_TABLAS", 5 ); //Se define el número de tablas que se va a crear. 

 $contador_variables_llegada = 0; 
	$cadena_informe_instalacion = ""; 
	$interrupcion_proceso = 0;
	$imprimir_mensajes_prueba = 0;


//variables
    if( isset( $_GET[ 'servidor' ] ) ) 		$contador_variables_llegada ++;
	if( isset( $_GET[ 'usuario' ] ) ) 		$contador_variables_llegada ++;
	if( isset( $_GET[ 'contrasena' ] ) ) 	$contador_variables_llegada ++;
	if( isset( $_GET[ 'bd' ] ) ) 			$contador_variables_llegada ++;

//desde aqui se trae la base de datos
$sql = file_get_contents( "../document/DB/nuevo.sql" );

//Borrado de variables inecesarios de la base de datos en las funciones 
$sql = str_replace("DELIMITER",'', $sql );
$sql = str_replace("$$",'', $sql );
$sql = str_replace("//",'', $sql );

$mensaje1 = "Es posible que la tabla o el objeto ya esté creada(o), por favor reinicie la instalación con una base de datos vacía.";

    if( $imprimir_mensajes_prueba == 1 ) echo "<br>Llegaron ".$contador_variables_llegada." variables.";
                
            if( $contador_variables_llegada >= 3 && $contador_variables_llegada <= 4 ) // Super if - inicio
            {
                if( $imprimir_mensajes_prueba == 1 ) echo "<br>Entrando al bloque de instalaci&oacute;n.";

                //Se realiza una sola conexión para la ejecución de todas las consultas SQL.-------------------------------
                //$conexion = @mysqli_connect( $_GET[ 'servidor' ], $_GET[ 'usuario' ], $_GET[ 'contrasena' ], $_GET[ 'bd' ] ); //Linea anterior, salía error de conexión.
                $conexion = @mysqli_connect( $_GET[ 'servidor' ], $_GET[ 'usuario' ], $_GET[ 'contrasena' ], $_GET[ 'bd' ] ); //Ojo, con el arroba no sale el mensaje de error.

                if( !$conexion ) //Verificamos que la conexion esté establecida preguntando si hay error o conexión no existe.
                {
                    $interrupcion_proceso = 1; //Si pasa a este bloque, la conexión no se ha establecido, quiere decir que activaremos la variable de interrupción.
                    $cadena_informe_instalacion .= "<br>Error: no se ha podido establecer una conexión con la base de datos. ";

                }else{

                        //echo "1 fds<br>".$objeto_verificador->mostrar_tablas( $conexion, 2 );

                        if( $objeto_verificador->mostrar_tablas( $conexion, 2 ) != 0 ) //Aquí se verifica que no hayan tablas existentes.
                        {
                            //echo "2 fds<br>";

                            echo "Ya hay tablas creadas, por favor cree una base de datos nueva.<br>"; 
                            $interrupcion_proceso = 1;
                        }
                    }
                 
                    if( $interrupcion_proceso == 0 )
                    {
                        //ojo aquí se usa la clase verificadora para imprimir lo que se ha creado.
                        echo $objeto_verificador->mostrar_tablas( $conexion ); //Hay que recordar que la conexión ya se creó arriba.
                
                      
                        
                        echo "<br><br>";
                        echo "<a href='borrando_archivos.php' target='_self'>Proceder a borrar archivos de intalaci&oacute;n</a>";
                        echo "<br><br>";
                    }
                    
                    echo $cadena_informe_instalacion; //Se imprime un sencillo informe de la instalación.
                
                }else{ 									// Super if - else 
                        echo "<br>Por favor ingresa el valor de los campos solicitados: Servidor, usuario, base de datos.<br>";
                    } 
   
    
      	/**
	*	Esta función se encarga de verificar si existe una tabla en el catálogo del sistema.
	*	@param 		texto 		el nombre de la tabla a buscar	
	*	@param 		texto 		el servidor para la conexión 
	*	@param 		texto 		el usuario para la conexión
	*	@param 		texto 		la contraseña para la conexión
	*	@param 		texto 		el nombre de la base de datos
	*	@return 	número 		un número con valores 0 o 1 para indicar o no la existencia de una tabla.
	*/ 

	function verificar_existencia_tabla( $tabla, $servidor, $usuario, $clave, $bd, $imp_pruebas = null )
	{
		$conteo = 0;

		$sql = " SELECT COUNT( * ) AS conteo FROM information_schema.tables WHERE table_schema = '$bd' AND table_name = '$tabla' ";
		if( $imp_pruebas == 1 ) echo "<br><strong>".$sql."</strong><br>";
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd  );
		$resultado = $conexion->query( $sql );

		while( $fila = mysqli_fetch_assoc( $resultado ) )
		{
			$conteo = $fila[ 'conteo' ]; //Si hay resultados la variable será afectada.
		}

		return $conteo;
	}

	/**
	*	Esta función se encarga de verificar si existe una restricción en el catálogo del sistema. Por supuesto esta función y la
	*	de búsqueda de tablas podría ser una sola, generalizando mejor y refactorizando el código.
	*	@param 		texto 		el nombre del objeto a buscar	
	*	@param 		texto 		el servidor para la conexión 
	*	@param 		texto 		el usuario para la conexión
	*	@param 		texto 		la contraseña para la conexión
	*	@param 		texto 		el nombre de la base de datos
	*	@return 	número 		un número con valores 0 o 1 para indicar o no la existencia de una tabla.
	*/
	function verificar_existencia_objeto( $objeto, $servidor, $usuario, $clave, $bd, $imp_pruebas = null )
	{
		$conteo = 0;

		//$sql = " SELECT COUNT( * ) AS conteo FROM information_schema.tables WHERE table_schema = '$bd' AND table_name = '$tabla' ";
		$sql = " SELECT COUNT( * ) AS conteo FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = '$bd' AND CONSTRAINT_NAME = '$objeto'; ";
		if( $imp_pruebas == 1 ) echo "<br><strong>".$sql."</strong><br>";
		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd  );
		$resultado = $conexion->query( $sql );

		while( $fila = mysqli_fetch_assoc( $resultado ) )
		{
			$conteo = $fila[ 'conteo' ]; //Si hay resultados la variable será afectada.
		}

		return $conteo;
	}
        







































//variables

/*$hostName = $_GET['servidor'];
$userName = $_GET['ususario'];
$pasword  = $_GET['contraseña'];
$dateBase = $_GET['base_de_datos'];*/

//desde aqui se trae la base de datos
/*$sql = file_get_contents( "../document/DB/nuevo.sql" );

//Borrado de variables inecesarios de la base de datos en las funciones 
$sql = str_replace("DELIMITER",'', $sql );
$sql = str_replace("$$",'', $sql );
$sql = str_replace("//",'', $sql );

$mensaje1 = "Es posible que la tabla o el objeto ya esté creada(o), por favor reinicie la instalación con una base de datos vacía.";


		//Se realiza una sola conexión para la ejecución de todas las consultas SQL.-------------------------------
		//$conexion = @mysqli_connect( $_GET[ 'servidor' ], $_GET[ 'usuario' ], $_GET[ 'contrasena' ], $_GET[ 'bd' ] ); //Linea anterior, salía error de conexión.
		$conexion = @mysqli_connect( $_GET[ 'servidor' ], $_GET[ 'usuario' ], $_GET[ 'contrasena' ], $_GET[ 'bd' ] ); //Ojo, con el arroba no sale el mensaje de error.

		if( !$conexion ) //Verificamos que la conexion esté establecida preguntando si hay error o conexión no existe.
		{   
            echo "<br>Error: no se ha podido establecer una conexión con la base de datos. ";

		}else{

				echo "1 fds<br>".$objeto_verificador->ingresar( $conexion, 2 );

				if( $objeto_verificador->ingresar( $conexion, 2 ) != 0 ) //Aquí se verifica que no hayan tablas existentes.
				{
					echo "2 fds<br>";

					echo "Ya hay tablas creadas, por favor cree una base de datos nueva.<br>"; 
				}
			}*/
        

/*$conexion = @mysqli_connect( 'localhost', 'usuario', 'contrasenas ', 'bd' );
//$conexion->multi_query($sql);
   
    if(!$conexion)
    {
        echo " El ususario o contraseña es incorreta". mysqli_connect_error();
    }else{

        $sql = file_get_contents( "../document/DB/nuevo.sql" );
    }*/
    
 
    
//echo $sql;

























 
//     include( "verificador.php" ); //Se incluye la clase verificador, la idea es no hacer este código más grande.
//     $objeto_verificador = new Verificador(); //Se crea la instancia de la clase verificador.

//     define( "numero_de_tablas", 3 ); //numer de tablas que se crearan.

//     $contador_variables_llegada = 0;
//     $cadena_informe_instalacion = ""; 
// 	$interrupcion_proceso = 0;
// 	$imprimir_mensajes_prueba = 0;  //Usar valores 0 o 1, solo para el programador.
// 	$tmp_nombre_objeto_o_tabla = "";

//     $mensaje1 = "Es posible que la tabla o el objeto ya esté creada(o), por favor reinicie la instalación con una base de datos vacía.";

// 	if( isset( $_GET[ 'servidor' ] ) ) 		$contador_variables_llegada ++;
// 	if( isset( $_GET[ 'usuario' ] ) ) 		$contador_variables_llegada ++;
// 	if( isset( $_GET[ 'contrasena' ] ) ) 	$contador_variables_llegada ++;
// 	if( isset( $_GET[ 'bd' ] ) ) 			$contador_variables_llegada ++;
 
//         if( $imprimir_mensajes_prueba == 1) echo "<br>Llegaron ".$contador_variables_llegada." variables.";

        
//         //en esta se cuenta las variables de llegada, por jemplo si escribes solo, el servidor, el usuario y la bases de datos, al final retorna a 4 ya que cuenta la contraseña, aunque no la hayas colocado.
        
// 	//Tienen que llegar cuatro variables para poder dar continuación al proceso de instalación.
// 	if( $contador_variables_llegada >= 3 && $contador_variables_llegada <= 4 ) // Super if - inicio
// 	{
//         if( $imprimir_mensajes_prueba = 1) echo "<br>Estrando al bloque de instalaci&oacute;n. ";
           
//            //$conexion = mysqli_connect( $_GET[ 'servidor '], $_GET[ 'usuario' ], $_GET[ 'contrasena' ], $_GET[ 'bd' ] );
//            $conexion = @mysqli_connect( $_GET[ 'servidor '], $_GET[ 'usuario' ], $_GET[ 'contrasena' ], $_GET[ 'bd' ] );

//            if( !$conexion )
//            {
//                $interrupcion_proceso = 1;
//                $cadena_informe_instalacion .= "<br>Error: no se ha podido establecer una conexión con la base de datos. ";

//            }else{

//                     if($objeto_verificador->mostrar_tablas( $conexion, 2 ) != 0 )
//                     {
//                         echo "ya hay tablas creadas, por favor cree una nueva base de datos.<br>";
//                         $interrupcion_proceso = 1;
//                     }
//                 }
//        
// /*********************************************** FIN ALTER TABLE********************************************************* */
		
// 		if( $interrupcion_proceso == 0 )
// 		{
// 			//ojo aquí se usa la clase verificadora para imprimir lo que se ha creado.
// 			echo $objeto_verificador->mostrar_tablas( $conexion ); //Hay que recordar que la conexión ya se creó arriba.

// 			echo "Se han creado ".$objeto_verificador->mostrar_tablas( $conexion, 2 )." tablas de ".numero_de_tablas." que se deb&iacute;an crear.  ";
			
// 			echo "<br><br>";
// 			echo "<a href='borrando_archivos.php' target='_self'>Proceder a borrar archivos de intalaci&oacute;n</a>";
// 			echo "<br><br>";
// 		}
		
// 		echo $cadena_informe_instalacion; //Se imprime un sencillo informe de la instalación.

// 	}else{ 									// Super if - else 
// 			echo "<br>Por favor ingresa el valor de los campos solicitados: Servidor, usuario, base de datos.<br>";
// 		} 									// Super if - final

// 	/*******************************************f u n c i o n e s*********************************************************************/

// 	/**
// 	*	Esta función se encarga de verificar si existe una tabla en el catálogo del sistema.
// 	*	@param 		texto 		el nombre de la tabla a buscar	
// 	*	@param 		texto 		el servidor para la conexión 
// 	*	@param 		texto 		el usuario para la conexión
// 	*	@param 		texto 		la contraseña para la conexión
// 	*	@param 		texto 		el nombre de la base de datos
// 	*	@return 	número 		un número con valores 0 o 1 para indicar o no la existencia de una tabla.
// 	*/
// 	function verificar_existencia_tabla( $tabla, $servidor, $usuario, $clave, $bd, $imp_pruebas = null )
// 	{
// 		$conteo = 0;

// 		$sql = " SELECT COUNT( * ) AS conteo FROM information_schema.tables WHERE table_schema = '$bd' AND table_name = '$tabla' ";
// 		if( $imp_pruebas == 1 ) echo "<br><strong>".$sql."</strong><br>";
// 		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd  );
// 		$resultado = $conexion->query( $sql );

// 		while( $fila = mysqli_fetch_assoc( $resultado ) )
// 		{
// 			$conteo = $fila[ 'conteo' ]; //Si hay resultados la variable será afectada.
// 		}

// 		return $conteo;
// 	}

// 	/**
// 	*	Esta función se encarga de verificar si existe una restricción en el catálogo del sistema. Por supuesto esta función y la
// 	*	de búsqueda de tablas podría ser una sola, generalizando mejor y refactorizando el código.
// 	*	@param 		texto 		el nombre del objeto a buscar	
// 	*	@param 		texto 		el servidor para la conexión 
// 	*	@param 		texto 		el usuario para la conexión
// 	*	@param 		texto 		la contraseña para la conexión
// 	*	@param 		texto 		el nombre de la base de datos
// 	*	@return 	número 		un número con valores 0 o 1 para indicar o no la existencia de una tabla.
// 	*/
// 	function verificar_existencia_objeto( $objeto, $servidor, $usuario, $clave, $bd, $imp_pruebas = null )
// 	{
// 		$conteo = 0;

// 		//$sql = " SELECT COUNT( * ) AS conteo FROM information_schema.tables WHERE table_schema = '$bd' AND table_name = '$tabla' ";
// 		$sql = " SELECT COUNT( * ) AS conteo FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = '$bd' AND CONSTRAINT_NAME = '$objeto'; ";
// 		if( $imp_pruebas == 1 ) echo "<br><strong>".$sql."</strong><br>";
// 		$conexion = mysqli_connect( $servidor, $usuario, $clave, $bd  );
// 		$resultado = $conexion->query( $sql );

// 		while( $fila = mysqli_fetch_assoc( $resultado ) )
// 		{
// 			$conteo = $fila[ 'conteo' ]; //Si hay resultados la variable será afectada.
// 		}

// 		return $conteo;
// 	}
?>