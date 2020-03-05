<?php  
//As classes Abstract não podem ser istanciadas somente herdadas, MF se trata das configuração do frameWork

//protected é protegido porem pode ser herdado, o private é somente daquele objeto

//Quando uma abstract class que defini um metodo abstract, quando a classe filho herda deve ser implementar o metodo

namespace MF\Init;

abstract class Bootstrap {

	private $routes;

	//metodo que devera ser implementado na classe filha
	abstract protected function initRotues();

	//Metodo que inicia ao instanciar a classe
	public function __construct(){

		//iniciando o array de rotas
		$this->initRotues();

		$this->run($this->getUrl());

	}

	//Metodos de manipulação do atributo routes
	public function getRoutes(){

		return $this->routes;

	}

	public function setRoutes(array $routes){

		$this->routes = $routes;

	}

	//Metodo que dispara dinamicamente a action e o controller // Metodo para executar a rota
	protected function run($url){

		//Primeiro recuperar o Path que é o route
		foreach ($this->getRoutes() as $path => $route) {
			if ($url == $route['route']) {

				//Segundo istanciando o IndexController de forma dinamica
				$class = "App\\Controllers\\".ucfirst($route['controller']);
				$controller = new $class;

				//Terceiro a action de forma dinamica
				$action = $route['action'];

				//Disparando de forma dinamica
				$controller->$action();

			}
		}

	}

	//Metodo que Obtem a URL // Metodo que recupera a Url que o usuario esta
	protected function getUrl(){

		//retornando a url aonde o usuario está
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	}
}

?>