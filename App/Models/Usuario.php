<?php  

namespace App\Models;

use MF\Model\Model;

/*
Classe que manipulo tb_usuarios do banco de dados
*/

class Usuario extends Model {

	private $id;
	private $nome;
	private $email;
	private $senha;

	public function __get($attr) {
		
		return $this->$attr;
	
	}

	public function __set($attr, $value) {
		
		$this->$attr = $value;
	
	}

	//metodo de registro do usuario
	public function salvar() {

		$query = 'insert into tb_usuarios(nome, email, senha) values(:n, :e, :s)';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':n', $this->__get('nome'));
		$stmt->bindValue(':e', $this->__get('email'));
		$stmt->bindValue(':s', $this->__get('senha'));

		$stmt->execute();

		return $this;

	}

	//metodo para validar se o cadastro pode ser feito
	public function validarCadastro() {

		$valido = true;

		if (strlen($this->__get('nome')) < 3) {
			
			$valido = false;

		}

		if (strlen($this->__get('email')) < 5) {
			
			$valido = false;

		}

		if (strlen($this->__get('senha')) < 4) {
			
			$valido = false;

		}

		return $valido;
	
	}

	//metodo que verifica se usuario ja existe
	public function getUsuarioEmail() {

		$query = 'select nome, email from tb_usuarios where email = :e';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':e', $this->__get('email'));

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//metodo que faz a ponte com o AuthController, e que eh responsavel por obter dados do banco de dados
	public function autenticar() {

		$query = 'select id, nome, email from tb_usuarios where email = :e and senha = :s';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':e', $this->__get('email'));
		$stmt->bindValue(':s', $this->__get('senha'));

		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);
		
		if ($usuario['id'] != '' && $usuario['nome'] != '') {
			
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);

		}
		
		return $usuario;

	}

	/*
	like pois o retorno do nomes eh maior, é obrigado a usar "%", metodo de pesquisa, para fazer uma sub consulta() que complementa o seguir e deixar de seguir
	*/
	public function getAll() {

		$query = '
			select 
				u.id, 
				u.nome, 
				u.email,
				(
					select count(*) from tb_usuarios_seguidores as us where us.id_usuario = :i and us.id_seguindo = u.id
				) as seguindo_sn
			from 
				tb_usuarios as u
			where 
				u.nome like :n and u.id != :i';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':n', '%'.$this->__get('nome').'%');
		$stmt->bindValue(':i', $this->__get('id'));

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	//metodo seguir Usuario
	public function seguir($id_seguindo) {

		$query = 'insert into tb_usuarios_seguidores(id_usuario, id_seguindo) values(:id_user, :id_seg)';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':id_user', $this->__get('id'));
		$stmt->bindValue(':id_seg', $id_seguindo);

		$stmt->execute();

		return true;
	}

	//Metodo de total de Tweets
	public function getTotalTweet() {

		$query = 'select count(*) as total_tweets from tweets where id_usuario = :i';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':i', $this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	//Metodo de total de Usuarios seguindo
	public function getTotalSeguindo() {

		$query = 'select count(*) as total_seguindo from tb_usuarios_seguidores where id_usuario = :i';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':i', $this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	////Metodo de total de Seguidores
	public function getTotalSeguidores() {

		$query = 'select count(*) as total_seguidores from tb_usuarios_seguidores where id_seguindo = :i';

		$stmt = $this->db->prepare($query);

		$stmt->bindValue(':i', $this->__get('id'));

		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

}

?>