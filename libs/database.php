<?php
	defined( 'uservice' ) or die( 'You should not see this.' );

	require_once(dirname(__FILE__).'/../config/config.inc.php');
	require_once(dirname(__FILE__).'/database/mapper.php');


	$GLOBALS['mapping_table'] = array(
		/**
		 * The user entity, represents the user of the system
		 */
		'users' => array(
			'type' => 'enetity',
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
		)

	);


?>
