<?php

namespace App;

/*
 Classe responsável pela a Conexão com o Banco de Dados 
 */
class Connection {

	/*
	protected $host = 'localhost';
	protected $dbname = 'mvc';
	protected $user = 'root';
	protected $pass = '';
	*/

	public static function getDb()	{
		
		try {
			//fazendo a conexao com o banco de dados
			$conn = new \PDO(
				"mysql:host=localhost;dbname=twitter_clone;charset=utf8",
				"root",
				""
			);

			return $conn;

		} catch (PDOException $e) {
			echo '<p> Error: '.$e->getCode().' Mensagem: '.$e->getMessage().'</p>';
		}
	}
}

?>