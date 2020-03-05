<?php  
//A action é uma abstarção do controller

//O MF se trata das abstract
namespace MF\Controller;
/*
 Classe abstrata das Action
 */
abstract class Action{
	
	protected $view;

	public function __construct() {

		/*
		É útil também utilizar a StdClass quando se deseja criar um objeto vazio e ir adicionando as propriedades conforme necessário.
		*/

		$this->view = new \stdClass();

	}
	
	//Metodo que vai ser responsavel por reinderizar o layout 
	protected function render($view, $layout = 'layout') {

		$this->view->page = $view;

		//verificar se o Layout existe
		if (file_exists("../App/Views/".$layout.".phtml")) {

			require_once "../App/Views/".$layout.".phtml";

		} else {

			$this->content();
		}
				

	}

	/*
	Metodo que contem o require_once das views, para ultilizar mais dados é so coloca a virgula e passar mais parametros, metodo do conteudo
	*/

	protected function content(){

		$classAtual =  get_class($this);

		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);

		$classAtual = strtolower(str_replace('Controller', '', $classAtual));

		require_once "../App/Views/".$classAtual."/".$this->view->page.".phtml";

	}
}

?>