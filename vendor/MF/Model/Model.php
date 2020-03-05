<?php  

namespace MF\Model;

/*
 Classe Abstrata Model, a abstração do model, pois é ela que diz como eh feito a conexao com o banco
 */
abstract class Model {
	
	protected $db;
	
	public function __construct(\PDO $db) {
		$this->db = $db;
	}
}

?>