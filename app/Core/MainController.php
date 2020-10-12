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
			$loader = new \Twig\Loader\FilesystemLoader("src/Views/".$data[0]);
			##  Aquí se añade el path de la ruta y el nombre del namespace para twig:
			##  Para más información: https://twig.symfony.com/doc/3.x/api.html
			$loader->addPath('app/Resources/Views', 'base');
			##  <-----  ##
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