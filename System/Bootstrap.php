<?php

class Bootstrap
{
	public function __construct()
	{
		$flag = false;
		if(isset($_GET['path'])) {
			$tokens = explode('/', rtrim($_GET['path'], '/'));
			$controllerName = ucfirst(array_shift($tokens));
			if(file_exists('Controllers/'.$controllerName.'.php')){
				$controller = new $controllerName();

				if(!empty($tokens)) {
					$actionName = array_shift($tokens);
					if (method_exists($controller, $actionName)) {
						$controller->{$actionName}(@$tokens);
					}
					else {
						// if action is not found
						$flag = true;
					}
				}
				else {
					// default action index
					$controller->index();
				}
			}
			else {
				// if no controller found, render an error page
				$flag = true;
			}	
		}
		else {
			// if no controller entered
			$controllerName = 'HomeController';
			$controller = new $controllerName();
			$controller->index();
		}	

		//Error page
		if ($flag) {
			$controllerName = 'Error404PageNotFoundController';
			$controller = new $controllerName();
			$controller->index();
		}
	}
}