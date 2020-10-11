<?php
namespace app\Core;

use Symfony\Component\Yaml\Yaml;
	
	class Router{
		public $routeController;
		public $uri;
		public $directorioController;		
		public $controller;
		public $method;
		public $param;

		public function __construct($uri){
			self::compareRoutes($uri);			
		}

		## ------------- METODOS SET ------------- ##

		public function setUri($uri){
			
		}

		public function setDirectorioController($directorioController){
			$this->directorioController = $directorioController;
		}

		public function setController($controller){			
			//$this->controller = $controller;
			$this->controller = 'src\\'.$this->directorioController."\\".$controller."Controller";
		}

		public function setMethod($method){
			$this->method = $method;
		}

		public function setParams($param){
			$this->param = $param;
		}

		## ------------- FIN METODOS SET ------------- ##


		## ------------- METODOS GET ------------- ##

		public function getUri(){
			return $this->uri;
		}

		public function getDirectorioController(){
			return $this->directorioController;
		}

		public function getController(){
			return $this->controller;
		}

		public function getMethod(){
			return $this->method."Action";
		}

		public function getParams(){
			return $this->param;
		}

		## ------------- FIN METODOS GET ------------- ##


		public function compareRoutes($uri){			
			//Comparar uri con ruta del routing.yml			

			//Eliminar último caracter si es un slash "/"			
			if($uri[(strlen($uri)-1)] === "/"){
				$uri = substr($uri, 0, (strlen($uri)-1) );
			}

			//Eliminar indice 0 y 1 del uri :carpetaRaiz: para quedarnos con la ruta siguiente
			for($i=0; $i<2; $i++){
				$strPos = strPos($uri, '/');
				if($i==0){
					$uri = substr($uri, $strPos+1, strlen($uri));
				}else{
					$uri = substr($uri, $strPos, strlen($uri));
				}
				//echo $i." ".$uri."<br>";				
			}
			
			//Ubicar yml
			$routing = Yaml::parseFile('src/Routes/routing.yml');
			//Recorrer yml
			foreach ($routing as $keyRouting => $valueRouting) {
				//echo "Ruta: ".$keyRouting."<br>";	
				$arrayUri = explode("/", $uri);
				$arrayValueRouting = explode("/", $valueRouting['ruta']);
				if(count($arrayUri) === count($arrayValueRouting)){
					for($i=0; $i<count($arrayUri); $i++){
						if($arrayUri[$i] === $arrayValueRouting[$i]){
							$controlador = $valueRouting['controller'];	
							$correcto = true;
							//echo "bien <br>";
						}else{
							if($i == (count($arrayUri)-1)){															
								$param = $arrayUri[$i];	
								//echo "Bien <br>";								
							}else{
								echo "Rout not found. <br>";
								$correcto = false;
								break;
							}														
						}
					}
				}else{
					echo "Route not found. <br>";
					echo "Verifique el routing yml para <strong>'$keyRouting'</strong><br>";
					echo "No coinciden las rutas. Cantidad de parámetros.";
					$correcto = false;
					break;
				}
			}

			
			//Setear los valores
			if($correcto){
				//Separar datos obtenidos del controlador
					if($controlador){
						$data = explode(":", $controlador);			
							$directorioController = $data[0];
							$controller 		 = $data[1];
							$method     		 = $data[2];	
					}			

				$this->setDirectorioController($directorioController);			
				$this->setController($controller);
				$this->setMethod($method);
				$this->setParams($param);	
			}
			
			
				
			
			/*
			echo "Directorio: $directorioController <br>";
			echo "Controller: $controller <br>";
			echo "Method: $method <br>";
			echo "Param: $param <br>";

			/*
			echo "<br><br><br>";
			var_dump($arrayUri);
			var_dump($arrayValueRouting);
			*/

		}
	}

?>