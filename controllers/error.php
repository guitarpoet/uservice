<?php
	require_once(dirname(__FILE__).'/../libs/common.php');
	class ErrorController {
		public function handle($smarty) {
			$view = get_param('view', 'error');
			$smarty->display($view.'.tpl');
		}
	}
?>
