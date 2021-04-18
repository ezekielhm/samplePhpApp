<?php

class View
{
	public function render($viewPath, $layout = null)
	{
		if ($layout === null) {
			$this->view = $viewPath;
			require('views/layout.php');
		}
		else if ($layout === false) {
			require('views/'.$viewpath.'.php');
		}
		else {
			$this->view = $viewPath;
			require("views/$layout.php");
		}
	}
}