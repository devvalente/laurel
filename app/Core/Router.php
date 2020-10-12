<?php
namespace app\Core;

use Symfony\Component\Yaml\Yaml;
use app\Core\MessageError;
	
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
			##  $this->controller = $controller;  ##
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
			##  Comparar uri con ruta del routing.yml  ##

			##  Eliminar último caracter si es un slash "/"  ##
			if($uri[(strlen($uri)-1)] === "/"){
				$uri = substr($uri, 0, (strlen($uri)-1) );
			}

			##  Eliminar indice 0 y 1 del uri :carpetaRaiz: para quedarnos con la ruta siguiente  ##
			for($i=0; $i<2; $i++){
				$strPos = strPos($uri, '/');
				if($i==0){
					$uri = substr($uri, $strPos+1, strlen($uri));
				}else{
					$uri = substr($uri, $strPos, strlen($uri));
				}
				##  echo $i." ".$uri."<br>";  ##
			}
			
			##  Ubicar yml  ##
			$routing = Yaml::parseFile('src/Routes/routing.yml');

			##  Contador para routing  ##
			$contR = 1;

			##  Para control  ##
			$correcto = false;
			$param = null;

			##  Para cortar proceso foreach :: si finalizarForeach entonces se corta, sino, se sigue  ##
			$finalizarForeach = false;

			##  Array con la url  ##
			$arrayUri    = explode("/", $uri);

			##  Recorrer array  ##
			foreach ($routing as $keys => $routes) {				
				//echo "<strong>Ruta: $keys <br></strong>";
				##  Saber si la ruta recibe parametros:  ##
				$contParam = stripos($routes['ruta'], "{") ? "true": "false";
				##  Desglosar el array routes  ##
				$arrayRoutes = explode("/", $routes['ruta']); 

				##  Verificar si los primeros indices coinciden  ##
					##  Para los que contienen params  ##
					if($contParam === 'true'){						
						//echo "Contiene parametros <br>";
						##  Si contiene parametros puede la uri se igual o menor que la ruta, pero no mayor  ##
						##  Si contiene los mismos elementos (parametros) la url que la route ##
						if( (count($arrayUri))===(count($arrayRoutes)) ){
							//echo "Contienen la misma cantidad de elementos. <br>";
							//echo "$keys <br>";
							for($i=0; $i<count($arrayUri); $i++){
								## Si los indices son iguales mientras no sea el último ##
								if( ($arrayUri[$i])===($arrayRoutes[$i]) && ($i < (count($arrayRoutes)-1)) ){
									$correcto = true;
									$param = $arrayUri[count($arrayUri)-1];
									//echo $arrayUri[$i].":".$arrayRoutes[$i]."<br>";
								##  Si los indices son diferentes antes del ultimo  ##
								}elseif( ($arrayUri[$i])!==($arrayRoutes[$i]) && ($i < (count($arrayRoutes)-1)) ){
									$correcto = false;									
									//echo $arrayUri[$i].":".$arrayRoutes[$i]."<br>";
								}								
								//echo "Correcto: $correcto <br>";
							}							
							## Decirle que es la ruta correcta y enviarla ##
							if($correcto){ $finalizarForeach=true; $ruta=$keys; }
						## Si la url contiene en elemento menos:: parametro por definicion automatica ##
						}elseif( (count($arrayUri))===(count($arrayRoutes)-1) ){
							//echo "Contienen un elemento menos. <br>";
							//echo "$keys <br>";
							for($i=0; $i<(count($arrayUri)); $i++){								
								## Si los indices son iguales :: aquí no aplica para el ultimo como en el caso anterior ##
								if( ($arrayUri[$i])===($arrayRoutes[$i]) ){
									//echo $arrayUri[$i];
									$correcto = true;									
								}else{		
									//echo $arrayUri[$i];							
									$correcto = 0;
									break;									
								}								
							}
							//echo "Correcto: $correcto <br>";
							## Decirle que es la ruta correcta y enviarla ##
							if($correcto){ $finalizarForeach=true; $ruta=$keys; }								
						}
					}
					##  Para los que no contienen parametros  ##
					elseif($contParam === 'false'){
						//echo "No contiene parametros <br>";
						##  Si no contiene parametros debe tener los mismos elementos  ##
						if( (count($arrayUri))===(count($arrayRoutes)) ){
							//echo "Contienen la misma cantidad de elementos. <br>";
							##  Recorrer arrays para revisarlos  ##
							for($i=0; $i<count($arrayRoutes); $i++){
								if( ($arrayUri[$i])===($arrayRoutes[$i]) ){
									$correcto = true;
								}else{
									$correcto = false;
								}
							}							
							## Decirle que es la ruta correcta y enviarla ##
							if($correcto){ $finalizarForeach=true; $ruta=$keys; }
						}elseif( (count($arrayUri))>(count($arrayRoutes)) ){
							$correcto = false;							
						}
					}

				##  Cortar foreach  ##
				if($finalizarForeach){
					//echo "<h2>Ruta encontrada:</h2>";
					//echo "Ruta encontrada: $keys <br>";
					break;
				}else{
					if($contR === (count($routing))){
						$messageError = new MessageError();
							$mensaje = [];
								$mensaje[]="Ruta no encontrada.";
								$mensaje[]="Ruta: $uri.";
							$messageError->setTipoMessage("Error en Ruta.");
							$messageError->setClassMessage("Fatal Error.");
							$messageError->setMessageError($mensaje);								
							$messageError->showMessage();
						die();
					}
				}
				$contR++;
			}

			$data = explode(":", $routing[$keys]['controller']);	
			$data[]=$param;			
				self::setDirectorioController($data[0]);
				self::setController($data[1]);
				self::setMethod($data[2]);
				self::setParams($data[3]);
				
			//self::debug($arrayUri, "URL");
			//self::debug($routing, "Routing");
			//self::debug($data, "Data");
			//die();
		}

		public function debug($data, $title){			
			echo "<h3>Debbug para: $title </h3>";
			echo "<pre>";
				var_dump($data);
			echo "</pre>";
			echo "<br>";
		}
	}

?>