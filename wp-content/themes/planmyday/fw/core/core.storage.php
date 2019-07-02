<?php
/**
 * Planmyday Framework: theme variables storage
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('planmyday_storage_get')) {
	function planmyday_storage_get($var_name, $default='') {
		global $PLANMYDAY_STORAGE;
		return isset($PLANMYDAY_STORAGE[$var_name]) ? $PLANMYDAY_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('planmyday_storage_set')) {
	function planmyday_storage_set($var_name, $value) {
		global $PLANMYDAY_STORAGE;
		$PLANMYDAY_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('planmyday_storage_empty')) {
	function planmyday_storage_empty($var_name, $key='', $key2='') {
		global $PLANMYDAY_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($PLANMYDAY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($PLANMYDAY_STORAGE[$var_name][$key]);
		else
			return empty($PLANMYDAY_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('planmyday_storage_isset')) {
	function planmyday_storage_isset($var_name, $key='', $key2='') {
		global $PLANMYDAY_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($PLANMYDAY_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($PLANMYDAY_STORAGE[$var_name][$key]);
		else
			return isset($PLANMYDAY_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('planmyday_storage_inc')) {
	function planmyday_storage_inc($var_name, $value=1) {
		global $PLANMYDAY_STORAGE;
		if (empty($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = 0;
		$PLANMYDAY_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('planmyday_storage_concat')) {
	function planmyday_storage_concat($var_name, $value) {
		global $PLANMYDAY_STORAGE;
		if (empty($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = '';
		$PLANMYDAY_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('planmyday_storage_get_array')) {
	function planmyday_storage_get_array($var_name, $key, $key2='', $default='') {
		global $PLANMYDAY_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($PLANMYDAY_STORAGE[$var_name][$key]) ? $PLANMYDAY_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($PLANMYDAY_STORAGE[$var_name][$key][$key2]) ? $PLANMYDAY_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('planmyday_storage_set_array')) {
	function planmyday_storage_set_array($var_name, $key, $value) {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if ($key==='')
			$PLANMYDAY_STORAGE[$var_name][] = $value;
		else
			$PLANMYDAY_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('planmyday_storage_set_array2')) {
	function planmyday_storage_set_array2($var_name, $key, $key2, $value) {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if (!isset($PLANMYDAY_STORAGE[$var_name][$key])) $PLANMYDAY_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$PLANMYDAY_STORAGE[$var_name][$key][] = $value;
		else
			$PLANMYDAY_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('planmyday_storage_set_array_after')) {
	function planmyday_storage_set_array_after($var_name, $after, $key, $value='') {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if (is_array($key))
			planmyday_array_insert_after($PLANMYDAY_STORAGE[$var_name], $after, $key);
		else
			planmyday_array_insert_after($PLANMYDAY_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('planmyday_storage_set_array_before')) {
	function planmyday_storage_set_array_before($var_name, $before, $key, $value='') {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if (is_array($key))
			planmyday_array_insert_before($PLANMYDAY_STORAGE[$var_name], $before, $key);
		else
			planmyday_array_insert_before($PLANMYDAY_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('planmyday_storage_push_array')) {
	function planmyday_storage_push_array($var_name, $key, $value) {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($PLANMYDAY_STORAGE[$var_name], $value);
		else {
			if (!isset($PLANMYDAY_STORAGE[$var_name][$key])) $PLANMYDAY_STORAGE[$var_name][$key] = array();
			array_push($PLANMYDAY_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('planmyday_storage_pop_array')) {
	function planmyday_storage_pop_array($var_name, $key='', $defa='') {
		global $PLANMYDAY_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($PLANMYDAY_STORAGE[$var_name]) && is_array($PLANMYDAY_STORAGE[$var_name]) && count($PLANMYDAY_STORAGE[$var_name]) > 0) 
				$rez = array_pop($PLANMYDAY_STORAGE[$var_name]);
		} else {
			if (isset($PLANMYDAY_STORAGE[$var_name][$key]) && is_array($PLANMYDAY_STORAGE[$var_name][$key]) && count($PLANMYDAY_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($PLANMYDAY_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('planmyday_storage_inc_array')) {
	function planmyday_storage_inc_array($var_name, $key, $value=1) {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if (empty($PLANMYDAY_STORAGE[$var_name][$key])) $PLANMYDAY_STORAGE[$var_name][$key] = 0;
		$PLANMYDAY_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('planmyday_storage_concat_array')) {
	function planmyday_storage_concat_array($var_name, $key, $value) {
		global $PLANMYDAY_STORAGE;
		if (!isset($PLANMYDAY_STORAGE[$var_name])) $PLANMYDAY_STORAGE[$var_name] = array();
		if (empty($PLANMYDAY_STORAGE[$var_name][$key])) $PLANMYDAY_STORAGE[$var_name][$key] = '';
		$PLANMYDAY_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('planmyday_storage_call_obj_method')) {
	function planmyday_storage_call_obj_method($var_name, $method, $param=null) {
		global $PLANMYDAY_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($PLANMYDAY_STORAGE[$var_name]) ? $PLANMYDAY_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($PLANMYDAY_STORAGE[$var_name]) ? $PLANMYDAY_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('planmyday_storage_get_obj_property')) {
	function planmyday_storage_get_obj_property($var_name, $prop, $default='') {
		global $PLANMYDAY_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($PLANMYDAY_STORAGE[$var_name]->$prop) ? $PLANMYDAY_STORAGE[$var_name]->$prop : $default;
	}
}
?>