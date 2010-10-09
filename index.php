<?php
	define('uservice', 1); // Set the uservice flag
	define('ROOT', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

	require_once(dirname(__FILE__).'/libs/fb.php');
	require_once(dirname(__FILE__).'/libs/Smarty.class.php');
	require_once(dirname(__FILE__).'/libs/lessc.inc.php');
	require_once(dirname(__FILE__).'/libs/common.php');

	$firephp = FirePHP::getInstance(true);
	$firephp->registerErrorHandler($throwErrorExceptions=true);
	$firephp->registerExceptionHandler();
	$firephp->registerAssertionHandler($convertAssertionErrorsToExceptions=true, $throwAssertionExceptions=false);

	date_default_timezone_set('Asia/Chongqing');

	$smarty = new Smarty;
	$smarty->force_compile = true;
	if(isset($_GET['debug']) || isset($_POST['debug']))
		$smarty->debugging = $_GET['debug'];
	$smarty->caching = true;
	$smarty->cache_lifetime = 120;
	$smarty->assign('title', 'UService');

	$GLOBALS['engine'] = $smarty;

	try {
		// Compile the less code to css
		lessc::ccompile(dirname(__FILE__).'/css/style.less', dirname(__FILE__).'/cache/style.css');

		// The controller logic
		$controller = get_param('operation', 'default');
		smooth_require_once(dirname(__FILE__).'/controllers/'.$controller.'.php');

		$controller = capitalize_words($controller).'Controller';
		$controller = new $controller;
		$controller->handle($smarty);
	} catch (exception $ex) {
		// In case any error happens
		$smarty->assign('title', _('Error!'));
		$smarty->assign('message', $ex->getMessage());
		$smarty->display('error.tpl');
	}
?>
