<?php
	define('uservice', 1); // Set the uservice flag
	define('ROOT', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

	require_once(dirname(__FILE__).'/libs/fb.php');
	require_once(dirname(__FILE__).'/libs/Smarty.class.php');
	require_once(dirname(__FILE__).'/libs/lessc.inc.php');
	require_once(dirname(__FILE__).'/libs/common.php');
	require_once(dirname(__FILE__).'/libs/database.php');

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
		
		$file = dirname(__FILE__).'/controllers/'.$controller.'.php';
		if(file_exists($file))
			require_once($file);
		else 
			throw new Exception(_(sprintf('Can\'t find controller "%s"!', $controller)));

		$user = array(
			'id' => 2,
			'email' => 'a@b.com',
			'username' => 'e',
			'password' => 'lixin'
		);
		Mapper::get_instance()->save_entity('users', $user);
		debug(Mapper::get_instance()->exec('list_all_users'));

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
