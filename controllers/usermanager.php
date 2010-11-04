<?php
	require_once(dirname(__FILE__).'/../libs/common.php');
	require_once(dirname(__FILE__).'/../libs/user_ops.php');

	class UsermanagerController {
		public function handle($smarty) {
			$format = get_param('format', 'html');
			if($format == 'json') {
				$type = get_param('type');
				switch($type) {
				case 'activate':
					$ids = explode(',',get_param('ids'));
					activate_users($ids);
					break;
				case 'update':
					update_user(get_param('id'), get_param('username'), get_param('email'), get_param('status'));
					break;
				case 'suspend':
					$ids = explode(',',get_param('ids'));
					suspend_users($ids);
					break;
				case 'delete':
					$ids = explode(',',get_param('ids'));
					delete_users($ids);
					break;
				}
				echo json_encode(array('message' => 'OK'));
			}
			else {
				$mapper = Mapper::get_instance();
				$condition = get_param('condition');
				if(isset($condition)) {
					switch($condition) {
					case 'username':
						$result = $mapper->exec('list_user_by_name', array('%'.get_param('query').'%'), get_param('page', 0) * ITEM_COUNT, ITEM_COUNT);
						break;
					case 'email':
						$result = $mapper->exec('list_user_by_email', array('%'.get_param('query').'%'), get_param('page', 0) * ITEM_COUNT, ITEM_COUNT);
						break;
					}
				}
				else {
					$result = $mapper->exec('list_all_users', array(), get_param('page', 0) * ITEM_COUNT, ITEM_COUNT);
				}
				$smarty->assign('count', $result->total);
				$smarty->assign('users', $result->results);
				$smarty->assign('page_count', ceil($result->total / ITEM_COUNT));
				$smarty->display('users/result.tpl');
			}
		}
	}
?>
