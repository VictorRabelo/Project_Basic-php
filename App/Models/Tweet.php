<?php  
//Responsavel pelo CRUD do banco de dados
namespace App\Models;

use MF\Model\Model;

/*
Classe que manipulo tweets do banco de dados
*/

class Tweet extends Model {

	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

	public function __get($attr) {
		
		return $this->$attr;
	
	}

	public function __set($attr, $value) {
		
		$this->$attr = $value;
	
	}

	//Salvar o tweet
	public function salvar() {

		$query = 'insert into tweets(id_usuario, tweet) values(:i, :t)';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':i', $this->__get('id_usuario'));
		$stmt->bindValue(':t', $this->__get('tweet'));

		$stmt->execute();

		return $this;

	}

	//Metodo que lista no timeline
	public function getAll() {

		/*
		where é a condição, para ter acesso a outra tabela, relacionando tabelas, formatando a data tbm tem que o usar o "as", organizando a hora e data tbm
		*/

		$query = '
			select 
				t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data 
			from 
				tweets as t
				left join tb_usuarios as u on (t.id_usuario = u.id)
			where 
				id_usuario = :i or t.id_usuario in (select id_seguindo from tb_usuarios_seguidores where id_usuario = :i)
			order by
				t.data desc';

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':i', $this->__get('id_usuario'));
		
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

	//metodo remove Tweet
	public function deixarSeguir() {
		
		$query = 'delete from tweets where id = :i';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':i', $this->__get('id'));

		$stmt->execute();

		return true;

	}

}

?>