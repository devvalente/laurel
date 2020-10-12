<?php

use app\Core\Router;

require './app/Core/autoload.php';



	$router = new Router($_SERVER['REQUEST_URI']);	
		$controlador = $router->getController();
		$method 	 = $router->getMethod();
		$param 		 = $router->getParams();			
	
		//Verificar si el controlador existe:
		if( file_exists("$controlador.php") ){
			require "$controlador.php";	
		}else{
			echo "No existe el controlador. <br>";
			die();
		}
		
		
		$cont = new $controlador();
			$cont->$method($param);



?>