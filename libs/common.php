<?php
	defined( 'uservice' ) or die( 'You should not see this.' );

	function get_param($name, $default) {
		if(isset($_GET[$name])){
			return $_GET[$name];
		}
		if(isset($_POST[$name])) {
			return $_POST[$name];
		}
		return $default;
	}

	function capitalize_words($s) {
		$str = strtolower($s);
		$cap = true;
		$ret = '';
		for($x = 0; $x < strlen($str); $x++){
			$letter = substr($str, $x, 1);
			if($letter == '.' || $letter == '!' || $letter == '?'){
			    $cap = true;
			}elseif($letter != ' ' && $cap == true){
			    $letter = strtoupper($letter);
			    $cap = false;
			}
			$ret .= $letter;
		} 
		return $ret;
	}

	function smooth_require_once($file) {
		if(file_exists($file)) {
		    require_once($file);
		} else {
		    throw(new Exception(_('File "').$file._('" does not exist.')));
		} 
	}

	function get_link($target, $type = 'view' ) {
		switch($type) {
		case 'view':
			return ROOT.'/index.php?view=$view';
		}
	}

	// Set this only because gettext is not available for me.
	function _($text) {
		return $text;
	}
?>