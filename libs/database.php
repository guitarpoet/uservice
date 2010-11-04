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
		/**
		 * List every user in the database
		 */
		'list_all_users' => array(
			'type' => 'query',
			'query' => 'select * from users',
			'count_query' => 'select count(*) as count from users',
			'oper' => 'select'
		),
		'list_user_by_name' => array(
			'type' => 'query',
			'query' => 'select * from users where username like ?',
			'count_query' => 'select count(*) from users where username like ?',
			'oper' => 'select'
		),
		'list_user_by_email' => array(
			'type' => 'query',
			'query' => 'select * from users where email like ?',
			'count_query' => 'select count(*) from users where email like ?',
			'oper' => 'select'
		)

	);


?>
