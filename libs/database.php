<?php
	defined( 'uservice' ) or die( 'You should not see this.' );

	require_once(dirname(__FILE__).'/../config/config.inc.php');
	require_once(dirname(__FILE__).'/database/mapper.php');


	$GLOBALS['mapping_table'] = array(
		/**
		 * The user entity, represents the user of the system
		 */
		'users' => array(
			'type' => 'entity',
			'fields' => array(
				'email',
				'username',
				'password',
				'register_time',
				'register_ip',
				'last_login_time',
				'last_login_ip',
				'login_count',
				'salt',
				'status'
			)
		),
		'groups' => array(
			'type' => 'entity',
			'fields' => array(
				'groupname',
				'description',
				'creation_time',
				'last_modification_time',
				'creator'
			)
		),
		'list_all_users' => array(
			'type' => 'query',
			'query' => 'select * from users',
			'count_query' => 'select count(*) as count from users',
			'oper' => 'select'
		),
		'list_all_groups' => array(
			'type' => 'query',
			'query' => 'select g.id, g.groupname, g.description, g.creation_time, g.last_modification_time, u.username from groups g, users u where g.creator = u.id',
			'count_query' => 'select count(*) as count from groups',
			'oper' => 'select'
		),
		'list_user_by_name' => array(
			'type' => 'query',
			'query' => 'select * from users where username like ?',
			'count_query' => 'select count(*) as count from users where username like ?',
			'oper' => 'select'
		),
		'list_user_by_email' => array(
			'type' => 'query',
			'query' => 'select * from users where email like ?',
			'count_query' => 'select count(*) as count from users where email like ?',
			'oper' => 'select'
		),
		'list_group_members' => array(
			'type' => 'query',
			'query' => 'select u.id, u.email, u.username, u.status from users as u join group_members as g where u.id = g.uid and g.gid = ?',
			'count_query' => 'select count(u.id) as count from users as u join group_members as g where u.id = g.uid and g.gid = ?',
			'oper' => 'select'
		),
		'list_group_members_by_username' => array(
			'type' => 'query',
			'query' => 'select u.id, u.email, u.username, u.status from users as u join group_members as g where u.id = g.uid and g.gid = ? and u.username like ?',
			'count_query' => 'select count(u.id) as count from users as u join group_members as g where u.id = g.uid and g.gid = ? and u.username like ?' ,
			'oper' => 'select'
		),
		'list_group_members_by_email' => array(
			'type' => 'query',
			'query' => 'select u.id, u.email, u.username, u.status from users as u join group_members as g where u.id = g.uid and g.gid = ? and u.email like ?',
			'count_query' => 'select count(u.id) as count from users as u join group_members as g where u.id = g.uid and g.gid = ? and u.email like ?' ,
			'oper' => 'select'
		),
		'add_group_members' => array(
			'type' => 'batch',
			'query' => 'insert into group_members (uid, gid) values(?, ?)',
			'oper' => 'insert'
		),
		'remove_group_members' => array(
			'type' => 'batch',
			'query' => 'delete from group_members where uid = ? and gid = ?',
			'oper' => 'delete'
		)
	);


?>
