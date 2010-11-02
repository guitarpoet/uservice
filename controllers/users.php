<?php
	require_once(dirname(__FILE__).'/../libs/common.php');
	require_once(dirname(__FILE__).'/../libs/user_ops.php');

	class UsersController {
		public function handle($smarty) {
			$mapper = Mapper::get_instance();
			$result = $mapper->exec('list_all_users');
			$smarty->assign('count', $result->total);
			$smarty->assign('users', $result->results);
			$smarty->display('users/result.tpl');
		}
	}
?>
