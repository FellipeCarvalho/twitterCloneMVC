<?php

namespace MF\Model;

use App\Connection; //para instanciar o banco

class Container {

	public static function getModel($model) {
		$class = "\\App\\Models\\".ucfirst($model); //vai chamar a classe
		
	    $conn = Connection::getDb(); //instacia o banco
		
		return new $class($conn); //depois, de instanciar o modelo que sera usado, retorna a conexao do banco
	}
}


?>