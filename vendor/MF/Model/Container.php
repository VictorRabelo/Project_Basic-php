<?php  
// quando se cria metodo static nao tem necessidade de istancia a classe
namespace MF\Model;

use App\Connection;
/*
 Classe Container, que vai receber o Model, objetivo retorna o modelo ja instanciado com a conexao
 */
class Container {
	
	public static function getModel($model)	{

		$class = "\\App\\Models\\".ucfirst($model);

		//intancia de conexao
		$conn = Connection::getDb();
		
		return new $class($conn);
	}
}
?>