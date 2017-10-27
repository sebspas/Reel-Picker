<?php
session_start();

/* GLOBAL */
define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', dirname (__FILE__) . DS);

/* APP */
define ('APP', 'app' . DS);

ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);

require_once  ROOT . APP . 'Config.class.php';
require_once ROOT . APP . 'Bd.class.php';

// CONFIG de TWIG
require_once 'vendor/autoload.php'; 

$loader = new Twig_Loader_Filesystem(ROOT . APP . 'view/');

$twig = new Twig_Environment($loader);

// redirection selon la page dans l'url
if (isset($_SESSION['login'])) {
	if (!empty($_GET['page']) && is_file(Config::$path['controller'].$_GET['page'].'.php') && $_GET['page'] != 'login') {
		require_once Config::$path['controller'] . $_GET['page'].'.php';
	} else {
		require_once Config::$path['controller'] . 'home.php';
	}
} else {
	require_once Config::$path['controller'] . 'login.php';
}

?>