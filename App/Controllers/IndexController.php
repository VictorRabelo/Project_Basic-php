<?php

//É uma maneira certa isolar os requesitos funcionais dos não funcionais
namespace App\Controllers;

//recursos do frame
use MF\Controller\Action;
use MF\Model\Container;

//Os Models
// use App\Models\Usuario;
// use App\Models\;


/*
 Classe que sera responsavel pelas actions,faz a ponte controller e view, controller e model
 */
class IndexController extends Action {

	//Action Index
	public function index() {
		
		$this->view->login = isset($_GET['login'])? $_GET['login'] : '';
		
		$this->render('index');

	}

	public function inscreverse() {

		$this->view->usuario = array(

			'nome' => '',
			'email' => '',
			'senha' => ''
			
		);
		
		$this->view->erroCadastro = false;

		$this->render('inscreverse');

	}

	public function registrar() {
		
		//instanciando a classe
		$usuario = Container::getModel('Usuario');

		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', md5($_POST['senha']));

		if ($usuario->validarCadastro() && count($usuario->getUsuarioEmail()) == 0) {

			$usuario->salvar();
				
			$this->render('cadastro');
			
		} else {

			$this->view->usuario = array(
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			);

			$this->view->erroCadastro = true;

			$this->render('inscreverse');

		}
		

	}

}

?>