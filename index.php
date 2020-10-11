<?php

use app\Core\Router;

require './app/Core/autoload.php';



	$router = new Router($_SERVER['REQUEST_URI']);	
		$controlador = $router->getController();
		$method 	 = $router->getMethod();
		$param 		 = $router->getParams();			
	
		require "$controlador.php";

		$cont = new $controlador();
			$cont->$method($param);



?>