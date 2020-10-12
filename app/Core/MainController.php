<?php
namespace app\Core;

	class MainController{
		##  VARS WORK WITH TWIG  ##
		private $loader;
		private $twig;

		public function __construct(){
			
		}

		## ------------- METODO WORKIN VIEW TWIG ------------- ##
		public function render($pathView, $array){
			//Procesar la data
			$data = explode(":", $pathView);
			$loader = new \Twig\Loader\FilesystemLoader($data[0]);
			$twig = new \Twig\Environment($loader);
			echo $twig->render($data[1], $array);
		}
		## ------------- END METODO WORKIN VIEW TWIG ------------- ##
			

		## ------------- METODO WORKIN DEBUG DATA ------------- ##
		public function debug($data, $title){
			echo "<h2> $title </h2>";
			echo "<pre>";
				var_dump($data);
			echo "</pre>";
			echo "<br>";
		}
		## ------------- END METODO WORKIN DEBUG DATA ------------- ##

	}

?>