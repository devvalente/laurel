<?php
namespace app\Core;

	class MessageError{
		private $tipoMessage;
		private $classMessage;
		private $messageError;

		public function __construct(){}

		## ------------- METODOS SET ------------- ##
		public function setTipoMessage($tipo){
			$this->tipoMessage = $tipo;
		}
		public function setClassMessage($class){
			$this->classMessage = $class;
		}
		public function setMessageError($message){
			$this->messageError = $message;
		}
		## ------------- FIN METODOS SET ------------- ##

		## ------------- METODOS GET ------------- ##
		public function getTipoMessage(){
			return $this->tipoMessage;
		}
		public function getClassMessage(){
			return $this->classMessage;
		}
		public function getMessageError(){
			return $this->messageError;			
		}
		## ------------- FIN METODOS GET ------------- ##


		public function showMessage(){
			$error = [];
				$error['type']=self::getTipoMessage();
				$error['class']=self::getClassMessage();
				$error['message']=self::getMessageError();
			echo json_encode($error, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);			
		}
		

	}

?>