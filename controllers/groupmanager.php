<?php
	require_once(dirname(__FILE__).'/../libs/common.php');
	require_once(dirname(__FILE__).'/../libs/user_ops.php');

	class GroupmanagerController {
		public function handle($smarty) {
			$format = get_param('format', 'html');
			if($format == 'json') {
				$type = get_param('type');
				switch($type) {
				case 'members':
					echo json_encode(list_group_members(get_param('gid'), get_param('page', 0), get_param('count', ITEM_COUNT)));
					return;
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
				$result = $mapper->exec('list_all_groups', array(), get_param('page', 0) * ITEM_COUNT, ITEM_COUNT);
				$smarty->assign('count', $result->total);
				$smarty->assign('groups', $result->results);
				$smarty->assign('page_count', ceil($result->total / ITEM_COUNT));
				$smarty->display('users/groupmanager.tpl');
			}
		}
	}
?>
