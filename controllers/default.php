<?php
	require_once(dirname(__FILE__).'/../libs/common.php');
	class DefaultController {
		public function handle($smarty) {
			$view = get_param('view', 'home');
			if(file_exists(dirname(__FILE__).'/../templates/'.$view.'.tpl'))
				$smarty->display($view.'.tpl');
			else 
				throw new Exception(_('View "').$view.'" not found!'); // Incase the view is not found, show the error page.
		}
	}
?>
