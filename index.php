<?php

define ('SITE_NAME', 'sample App'); // added constant variable to avoid direct access

spl_autoload_register(function ($className) {
	if (file_exists('System/'.$className.'.php')){
		require_once 'System/'.$className.'.php';
	} 
	else if (file_exists('Controllers/'.$className.'.php')){
		require_once 'Controllers/'.$className.'.php';
	}
	else if(file_exists('Models/'.$className.'.php')){
		require_once 'Models/'.$className.'.php';
	}
	else if(file_exists($className.'.php')){
		require_once $className.'.php';
	}
});

new Bootstrap();
