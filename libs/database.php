<?php
	require_once(dirname(__FILE__)."/../config/config.inc.php");

	function get_db() {
		$db = new mysqli_Extended(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
		if (mysqli_connect_errno()) { 
			throw new Exception(sprintf("Connect failed: %s\n", mysqli_connect_error())); 
		} 
		return $db;	
	}

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
		)
	);

	class mysqli_Extended extends mysqli {
	    protected $selfReference;

	    public function __construct($dbHost, $dbUsername, $dbPassword, $dbDatabase) {
		parent::__construct($dbHost, $dbUsername, $dbPassword, $dbDatabase);

	    }

	    public function prepare($query) {
		$stmt = new stmt_Extended($this, $query);
		return $stmt;
	    }
	}

	class stmt_Extended extends mysqli_stmt {
	    protected $varsBound = false;
	    protected $results;

	    public function __construct($link, $query) {
		parent::__construct($link, $query);
	    }

	    public function fetch_assoc() {
		// checks to see if the variables have been bound, this is so that when
		//  using a while ($row = $this->stmt->fetch_assoc()) loop the following
		// code is only executed the first time
		if (!$this->varsBound) {
		    $meta = $this->result_metadata();
		    while ($column = $meta->fetch_field()) {
			// this is to stop a syntax error if a column name has a space in
			// e.g. "This Column". 'Typer85 at gmail dot com' pointed this out
			$columnName = str_replace(' ', '_', $column->name);
			$bindVarArray[] = &$this->results[$columnName];
		    }
		    call_user_func_array(array($this, 'bind_result'), $bindVarArray);
		    $this->varsBound = true;
		}

		if ($this->fetch() != null) {
		    // this is a hack. The problem is that the array $this->results is full
		    // of references not actual data, therefore when doing the following:
		    // while ($row = $this->stmt->fetch_assoc()) {
		    // $results[] = $row;
		    // }
		    // $results[0], $results[1], etc, were all references and pointed to
		    // the last dataset
		    foreach ($this->results as $k => $v) {
			$results[$k] = $v;
		    }
		    return $results;
		} else {
		    return null;
		}
	    }
	}

	class Mapper {
		private static $instance;

		private function __construct() {
			// For singleton
		}

		public static function get_instance() {
			if(!isset(self::$instance)) {
				self::$instance = new Mapper();
			}
			return self::$instance; 
		}

		public function load_entity($entity, $id) {
			if(isset($GLOBALS['mapping_table'][$entity])){
				$mapping = $GLOBALS['mapping_table'][$entity];
				$query = sprintf('select %s from %s where id = ?', implode(',', $mapping['fields']), $entity);
				$db = get_db();
				$stmt = $db->prepare($query);
				debug($query);
				$stmt->bind_param('i', $id);
				$stmt->execute();
				$stmt->store_result();
				while($row = $stmt->fetch_assoc()){
					return $row;
				}
			}
			return null;
		}
	}
?>
