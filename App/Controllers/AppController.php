<?php
namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

/*
Classe resposavel pela recebimento/envio dos dados do/para o model e ação da aplicaçao, manipula a view tbm
*/
class AppController extends Action {

	//Metodo recebe o redirecionamento do AuthController e que verifica se esta autenticado o usuario, e mostra a view timeline
	public function timeline() {

			//faz a validação e inicia a sessão
			$this->validaAutenticacao();

			//tratativa de recuperação dos tweets
			$tweet = Container::getModel('Tweet');

			$tweet->__set('id_usuario', $_SESSION['id']);

			$tweets = $tweet->getAll();

			//responsavel por levar os dados do back-end para ser trabalho no front
			$this->view->tweets = $tweets;

			$usuario = Container::getModel('Usuario');

			$usuario->__set('id', $_SESSION['id']);

			$this->view->totalTweets = $usuario->getTotalTweet();
			$this->view->totalSeguidores = $usuario->getTotalSeguidores();
			$this->view->totalSeguindo = $usuario->getTotalSeguindo();

			//render é ação que mostra a view
			$this->render('timeline');

	}

	//Action
	public function tweet() {

			$this->validaAutenticacao();

			$tweet = Container::getModel('Tweet');

			$tweet->__set('tweet', $_POST['tweet']);
			$tweet->__set('id_usuario', $_SESSION['id']);

			$tweet->salvar();

			header('Location: /timeline');
		
	}

	//Metodo de pesquisa por usuario
	public function quemSeguir() {

			$this->validaAutenticacao();

			$pesquisar = isset($_GET['pesquisar']) ? $_GET['pesquisar'] : '';

			$usuarios = array();

			if($pesquisar != '') {

				$usuario = Container::getModel('Usuario');

				$usuario->__set('nome', $pesquisar);
				$usuario->__set('id', $_SESSION['id']);
				$usuarios = $usuario->getAll();

			}

			$this->view->usuarios = $usuarios;

			$usuario = Container::getModel('Usuario');

			$usuario->__set('id', $_SESSION['id']);

			$this->view->totalTweets = $usuario->getTotalTweet();
			$this->view->totalSeguidores = $usuario->getTotalSeguidores();
			$this->view->totalSeguindo = $usuario->getTotalSeguindo();

			//render é ação que mostra a view
			$this->render('quemSeguir');
		
	}

	//Metodo responsavel por seguir ou deixar de seguir
	public function acao() {
		
		$this->validaAutenticacao();

		$action = isset($_GET['acao']) ? $_GET['acao'] : '';
		$id_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

		$usuario = Container::getModel('Usuario');

		$usuario->__set('id', $_SESSION['id']);


		if ($action == 'seguir') {
			
			$usuario->seguir($id_seguindo);

		} else if ($action == 'deixar_de_seguir') {
			
			$usuario->deixarSeguir($id_seguindo);

		}

		header('Location: /quem_seguir');

	}

	//Metodo responsavel por remover Tweet
	public function removerTweet() {

		$this->validaAutenticacao();

		$tweet = Container::getModel('Tweet');

		$tweet->__set('id', $_GET['id']);

		$tweet->deixarSeguir();

		header('Location: /timeline');
	}

	//Metodo que abstrai algo que é comum, metodo que valida o acesso
	public function validaAutenticacao() {

		session_start();

		if (!isset($_SESSION['id']) || $_SESSION['id'] == '' || !isset($_SESSION['nome']) || $_SESSION['nome'] == '') {

			header('Location: /?login=erro1');

		}
	}

}

?>