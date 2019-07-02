<?php
/**
 * Planmyday Framework: http queries and data manipulations
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


// Get GET, POST value
if (!function_exists('planmyday_get_value_gp')) {
	function planmyday_get_value_gp($name, $defa='') {
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		}
		return $rez;
	}
}


// Get GET, POST, SESSION value and save it (if need)
if (!function_exists('planmyday_get_value_gps')) {
	function planmyday_get_value_gps($name, $defa='', $page='') {
		$putToSession = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_SESSION[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_SESSION[$name.($page!='' ? '_'.($page) : '')]));
			$putToSession = false;
		}
		if ($putToSession)
			planmyday_set_session_value($name, $rez, $page);
		return $rez;
	}
}

// Get GET, POST, COOKIE value and save it (if need)
if (!function_exists('planmyday_get_value_gpc')) {
	function planmyday_get_value_gpc($name, $defa='', $page='', $exp=0) {
		$putToCookie = $page!='';
		$rez = $defa;
		if (isset($_GET[$name])) {
			$rez = stripslashes(trim($_GET[$name]));
		} else if (isset($_POST[$name])) {
			$rez = stripslashes(trim($_POST[$name]));
		} else if (isset($_COOKIE[$name.($page!='' ? '_'.($page) : '')])) {
			$rez = stripslashes(trim($_COOKIE[$name.($page!='' ? '_'.($page) : '')]));
			$putToCookie = false;
		}
		if ($putToCookie)
			setcookie($name.($page!='' ? '_'.($page) : ''), $rez, $exp, '/');
		return $rez;
	}
}

// Save value into session
if (!function_exists('planmyday_set_session_value')) {
	function planmyday_set_session_value($name, $value, $page='') {
		if (!session_id()) session_start();
		$_SESSION[$name.($page!='' ? '_'.($page) : '')] = $value;
	}
}

// Save value into session
if (!function_exists('planmyday_del_session_value')) {
	function planmyday_del_session_value($name, $page='') {
		if (!session_id()) session_start();
		unset($_SESSION[$name.($page!='' ? '_'.($page) : '')]);
	}
}


/* Other functions
-------------------------------------------------------------------------------- */

// Return current site protocol
if (!function_exists('planmyday_get_protocol')) {
	function planmyday_get_protocol() {
		return is_ssl() ? 'https' : 'http';
	}
}

?>