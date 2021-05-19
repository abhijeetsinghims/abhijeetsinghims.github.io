<?php
/**
 * Theme storage manipulations
 *
 * @package WordPress
 * @subpackage PATHWELL
 * @since PATHWELL 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('pathwell_storage_get')) {
	function pathwell_storage_get($var_name, $default='') {
		global $PATHWELL_STORAGE;
		return isset($PATHWELL_STORAGE[$var_name]) ? $PATHWELL_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('pathwell_storage_set')) {
	function pathwell_storage_set($var_name, $value) {
		global $PATHWELL_STORAGE;
		$PATHWELL_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('pathwell_storage_empty')) {
	function pathwell_storage_empty($var_name, $key='', $key2='') {
		global $PATHWELL_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($PATHWELL_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($PATHWELL_STORAGE[$var_name][$key]);
		else
			return empty($PATHWELL_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('pathwell_storage_isset')) {
	function pathwell_storage_isset($var_name, $key='', $key2='') {
		global $PATHWELL_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($PATHWELL_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($PATHWELL_STORAGE[$var_name][$key]);
		else
			return isset($PATHWELL_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('pathwell_storage_inc')) {
	function pathwell_storage_inc($var_name, $value=1) {
		global $PATHWELL_STORAGE;
		if (empty($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = 0;
		$PATHWELL_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('pathwell_storage_concat')) {
	function pathwell_storage_concat($var_name, $value) {
		global $PATHWELL_STORAGE;
		if (empty($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = '';
		$PATHWELL_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('pathwell_storage_get_array')) {
	function pathwell_storage_get_array($var_name, $key, $key2='', $default='') {
		global $PATHWELL_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($PATHWELL_STORAGE[$var_name][$key]) ? $PATHWELL_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($PATHWELL_STORAGE[$var_name][$key][$key2]) ? $PATHWELL_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('pathwell_storage_set_array')) {
	function pathwell_storage_set_array($var_name, $key, $value) {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if ($key==='')
			$PATHWELL_STORAGE[$var_name][] = $value;
		else
			$PATHWELL_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('pathwell_storage_set_array2')) {
	function pathwell_storage_set_array2($var_name, $key, $key2, $value) {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if (!isset($PATHWELL_STORAGE[$var_name][$key])) $PATHWELL_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$PATHWELL_STORAGE[$var_name][$key][] = $value;
		else
			$PATHWELL_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Merge array elements
if (!function_exists('pathwell_storage_merge_array')) {
	function pathwell_storage_merge_array($var_name, $key, $value) {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if ($key==='')
			$PATHWELL_STORAGE[$var_name] = array_merge($PATHWELL_STORAGE[$var_name], $value);
		else
			$PATHWELL_STORAGE[$var_name][$key] = array_merge($PATHWELL_STORAGE[$var_name][$key], $value);
	}
}

// Add array element after the key
if (!function_exists('pathwell_storage_set_array_after')) {
	function pathwell_storage_set_array_after($var_name, $after, $key, $value='') {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if (is_array($key))
			pathwell_array_insert_after($PATHWELL_STORAGE[$var_name], $after, $key);
		else
			pathwell_array_insert_after($PATHWELL_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('pathwell_storage_set_array_before')) {
	function pathwell_storage_set_array_before($var_name, $before, $key, $value='') {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if (is_array($key))
			pathwell_array_insert_before($PATHWELL_STORAGE[$var_name], $before, $key);
		else
			pathwell_array_insert_before($PATHWELL_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('pathwell_storage_push_array')) {
	function pathwell_storage_push_array($var_name, $key, $value) {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($PATHWELL_STORAGE[$var_name], $value);
		else {
			if (!isset($PATHWELL_STORAGE[$var_name][$key])) $PATHWELL_STORAGE[$var_name][$key] = array();
			array_push($PATHWELL_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('pathwell_storage_pop_array')) {
	function pathwell_storage_pop_array($var_name, $key='', $defa='') {
		global $PATHWELL_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($PATHWELL_STORAGE[$var_name]) && is_array($PATHWELL_STORAGE[$var_name]) && count($PATHWELL_STORAGE[$var_name]) > 0) 
				$rez = array_pop($PATHWELL_STORAGE[$var_name]);
		} else {
			if (isset($PATHWELL_STORAGE[$var_name][$key]) && is_array($PATHWELL_STORAGE[$var_name][$key]) && count($PATHWELL_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($PATHWELL_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('pathwell_storage_inc_array')) {
	function pathwell_storage_inc_array($var_name, $key, $value=1) {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if (empty($PATHWELL_STORAGE[$var_name][$key])) $PATHWELL_STORAGE[$var_name][$key] = 0;
		$PATHWELL_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('pathwell_storage_concat_array')) {
	function pathwell_storage_concat_array($var_name, $key, $value) {
		global $PATHWELL_STORAGE;
		if (!isset($PATHWELL_STORAGE[$var_name])) $PATHWELL_STORAGE[$var_name] = array();
		if (empty($PATHWELL_STORAGE[$var_name][$key])) $PATHWELL_STORAGE[$var_name][$key] = '';
		$PATHWELL_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('pathwell_storage_call_obj_method')) {
	function pathwell_storage_call_obj_method($var_name, $method, $param=null) {
		global $PATHWELL_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($PATHWELL_STORAGE[$var_name]) ? $PATHWELL_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($PATHWELL_STORAGE[$var_name]) ? $PATHWELL_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('pathwell_storage_get_obj_property')) {
	function pathwell_storage_get_obj_property($var_name, $prop, $default='') {
		global $PATHWELL_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($PATHWELL_STORAGE[$var_name]->$prop) ? $PATHWELL_STORAGE[$var_name]->$prop : $default;
	}
}
?>