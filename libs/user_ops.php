<?php
	defined( 'uservice' ) or die( 'You should not see this.' );

	define('USER_NOT_ACTIVE', 0);
	define('USER_LOGIN', 1);
	define('USER_SUSPEND', 2);
	define('USER_LOGOUT', 3);

	require_once(dirname(__FILE__).'/database.php');

	function user_op_event($name, $message) {
		EventDispatcher::get_instance()->dispatch($name, $message,'uservice',array('type' => 'user_op'));
	}

	function change_password($name_or_email, $new_password) {
		$mapper = Mapper::get_instance();
		$user = $mapper->load_by_fields('users', array(
			'username' => $name_or_email,
			'email' => $name_or_email
		), 'or');

		if(!isset($user)) 
			throw new Exception(_(sprintf('User Name or Email %s is not exist!'), $name_or_email));

		$to_update = array(
			'id' => $user['id'],
			'password' => md5($new_password.'@'.$user['salt'])
		);

		user_op_event('change_passwd', sprintf('Changing the password from %s to %s', $user['password'], $to_update['password']));

		$mapper->save_entity('users', $to_update);
		return true;
	}

	function reset_password($name_or_email) {
		throw new Exception('Not implemented yet');
	}

	function register_user($email, $username, $password) {
		// Check for username and password
		$mapper = Mapper::get_instance();
		$user = $mapper->load_by_fields('users', array(
			'username' => $username,
			'email' => $email,
		), 'or');
		if(isset($user))
			throw new Exception(_(sprintf('Username %s or Email %s is used by other people.', $username, $password)));
		$salt = get_salt();
		$user = array(
			'email' => $email,
			'username' => $username,
			'password' => md5($password.'@'.$salt),
			'register_time' => date('c'),
			'salt' => $salt,
			'register_ip' => $_SERVER['REMOTE_ADDR']
		);
		user_op_event('user_created', sprintf('Creating user with usernmae %s and email %s', $username, $password));
		return $mapper->save_entity('users', $user);
	}

	function load_user($name_or_email) {
		$mapper = Mapper::get_instance();
		return $mapper->load_by_fields('users', array(
			'username' => $name_or_email,
			'email' => $name_or_email,
		), 'or');
	}

	function update_user($id, $username, $email, $status) {
		$mapper = Mapper::get_instance();
		user_op_event('user_update', sprintf('Update user with username %s and email %s', $username, $email));
		$mapper->save_entity('users', array(
			'id' => $id,
			'username' => $username,
			'email' => $email,
			'status' => $status
		));
	}

	function login_user($name_or_email, $password) {
		$mapper = Mapper::get_instance();
		$user = $mapper->load_by_fields('users', array(
			'username' => $name_or_email,
			'email' => $name_or_email,
		), 'or');
		if(!isset($user))
			return false;
		if($user['password'] != md5($password.'@'.$user['salt']))
			return false;

		if(!isset($_SESSION))
			session_start();
		// If already logined, just do as logined
		if(isset($_SESSION['uid']))
			return true;

		user_op_event('user_login', sprintf('Login user with username %s and password %s', $username, $password));
		// Save the user information into session
		$_SESSION['uid'] = $user['id'];
		$_SESSION['username'] = $user['username'];

		// Update the last login ip and time
		$update = array(
			'id' => $user['id'],
			'last_login_time' => date('c'),
			'last_login_ip' => $_SERVER['REMOTE_ADDR'],
			'status' => USER_LOGIN
		);
		$mapper->save_entity('users', $update);

		return true;
	}

	function logout_user($name_or_email) {
		unset($_SESSION['uid']);
		session_destroy();
	}

	function delete_user($name_or_email) {
		$user = $this->load_user($name_or_email);
		if(isset($user)) {
			$mapper = Mapper::get_instance();
			user_op_event('user_delete', sprintf('Delete user with username %s or email %s', $name_or_email));
			$mapper->delete_entity('users', $user['id']);
		}
	}

	function delete_users($ids) {
		$mapper = Mapper::get_instance();
		user_op_event('user_delete', sprintf('Suspend users %s', implode(',', $ids)));
		foreach($ids as $id) {
			$mapper->delete_entity('users', $id);
		}
	}

	function suspend_users($ids) {
		$mapper = Mapper::get_instance();
		user_op_event('user_suspend', sprintf('Suspend users %s', implode(',', $ids)));
		foreach($ids as $id) {
			$mapper->save_entity('users', array(
				'id' => $id,
				'status' => USER_SUSPEND
			));
		}
	}

	function activate_users($ids) {
		$mapper = Mapper::get_instance();
		user_op_event('user_activate', sprintf('Suspend users %s', implode(',', $ids)));
		foreach($ids as $id) {
			$mapper->save_entity('users', array(
				'id' => $id,
				'status' => USER_LOGOUT
			));
		}
	}

	function suspend_user($name_or_email) {
		$user = $this->load_user($name_or_email);
		if(isset($user)) {
			user_op_event('user_suspend', sprintf('Suspend user with username %s or email %s', $name_or_email));
			$update = array(
				'id' => $user['id'],
				'status' => USER_SUSPEND
			);
			$mapper = Mapper::get_instance();
			$mapper->save_entity($update);
		}
	}

	function activate_user($name_or_email) {
		$user = $this->load_user($name_or_email);
		if(isset($user)) {
			user_op_event('user_suspend', sprintf('Activate user with username %s or email %s', $name_or_email));
			$update = array(
				'id' => $user['id'],
				'status' => USER_LOGOUT
			);
			$mapper = Mapper::get_instance();
			$mapper->save_entity($update);
		}
	}

	function load_group($gid) {
		$mapper = Mapper::get_instance();
		user_op_event('load_group', sprintf('Load group with group id %i', $gid));
		return $mapper->load_entity('groups', $gid);
	}

	function list_group_members($gid, $page = 0, $items = ITEM_COUNT) {
		$mapper = Mapper::get_instance();
		return $mapper->exec('list_group_members', array($gid), $page, $items);
	}

	function search_group_members($gid, $condition, $query, $page = 0, $items = ITEM_COUNT) {
		$mapper = Mapper::get_instance();
		user_op_event('search_group_members', sprintf('Search group members with group id %i and condition %s and query %s', $gid, $condition, $query));
		switch($condition) {
		case 'username':
			return $mapper->exec('list_group_members_by_username', array($gid, '%'.$query.'%'), $page, $items);
		case 'email':
			return $mapper->exec('list_group_members_by_email', array($gid, '%'.$query.'%'), $page, $items);
		}
	}

	function add_group_members($gid, $ids) {
		$mapper = Mapper::get_instance();
		$params = array();
		user_op_event('add_group_members', sprintf('Add group members with group id %i and members %s', $gid, implode(',', $ids)));
		foreach($ids as $uid) {
			$params []= array($uid, $gid);
		}
		$mapper->batch_query('add_group_members', $params);
	}

	function remove_group_members($gid, $ids) {
		$mapper = Mapper::get_instance();
		user_op_event('remove_group_members', sprintf('Remove group members with group id %i and members %s', $gid, implode(',', $ids)));
		$params = array();
		foreach($ids as $uid) {
			$params []= array($uid, $gid);
		}
		$mapper->batch_query('remove_group_members', $params);
	}
	function update_group($gid, $groupname, $description) {
		$mapper = Mapper::get_instance();
		user_op_event('update_group', sprintf('Update group with group id %i and groupname %s, description %s', $gid, $groupname, $description));
		$mapper->save_entity('groups', array(
			'id' => $gid,
			'groupname' => $groupname,
			'description' => $description,
			'last_modification_time' => date('c')
		));
	}
	function delete_groups($ids) {
		$mapper = Mapper::get_instance();
		user_op_event('remove_groups', sprintf('Remove groups %s', implode(',', $ids)));
		foreach($ids as $id) {
			$mapper->delete_entity('groups', $id);
		}
	}
	function create_group($groupname, $description, $creator) {
		$mapper = Mapper::get_instance();
		user_op_event('update_group', sprintf('Create group with group creator %i and groupname %s, description %s', $creator, $groupname, $description));
		return $mapper->save_entity('groups', array(
			'groupname' => $groupname,
			'description' => $description,
			'creation_time' => date('c'),
			'last_modification_time' => date('c'),
			'creator' => $creator
		));
	}
?>
