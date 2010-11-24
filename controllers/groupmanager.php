<?php
	require_once(dirname(__FILE__).'/../libs/common.php');
	require_once(dirname(__FILE__).'/../libs/user_ops.php');

	class GroupmanagerController {
		public function handle($smarty) {
			$format = get_param('format', 'html');
			if($format == 'json') {
				header('Content-Type: application/json');
				$type = get_param('type');
				switch($type) {
				case 'add_members':
					$ids = explode(',', get_param('ids'));
					add_group_members(get_param('gid'), $ids);
					break;
				case 'remove_members':
					$ids = explode(',', get_param('ids'));
					remove_group_members(get_param('gid'), $ids);
					break;
				case 'members':
					echo json_encode(list_group_members(get_param('gid'), get_param('page', 0) * ITEM_COUNT, get_param('count', ITEM_COUNT)));
					return;
				case 'insert':
					$gid = create_group(get_param('groupname'), get_param('description'), $_SESSION['uid']);
					echo json_encode('{"gid":'.$gid.'}');
					return;
				case 'update':
					update_group(get_param('id'), get_param('groupname'), get_param('description'));
					break;
				case 'delete':
					$ids = explode(',',get_param('ids'));
					delete_groups($ids);
					break;
				}
				echo json_encode(array('message' => 'OK'));
			}
			else {
				$gid = get_param('gid');
				if(isset($gid)) {
					$query = get_param('query');
					$group = load_group($gid);
					if(isset($query)) {
						$result = search_group_members($gid, get_param('condition', 'username'), get_param('query'), get_param('page', 0), ITEM_COUNT);
					}
					else {
						$result = list_group_members($gid);
					}
					$smarty->assign('gid', $gid);
					$smarty->assign('count', $result->total);
					$smarty->assign('users', $result->results);
					$smarty->assign('page_count', ceil($result->total / ITEM_COUNT));
					$smarty->assign('title', _(sprintf('Members of group %s members', $group['groupname'])));
					$smarty->display('users/groupmembers.tpl');
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
	}
?>
