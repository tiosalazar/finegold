<?php
/**
 * Planmyday Framework: strings manipulations
 *
 * @package	planmyday
 * @since	planmyday 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'PLANMYDAY_MULTIBYTE' ) ) define( 'PLANMYDAY_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('planmyday_strlen')) {
	function planmyday_strlen($text) {
		return PLANMYDAY_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('planmyday_strpos')) {
	function planmyday_strpos($text, $char, $from=0) {
		return PLANMYDAY_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('planmyday_strrpos')) {
	function planmyday_strrpos($text, $char, $from=0) {
		return PLANMYDAY_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('planmyday_substr')) {
	function planmyday_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = planmyday_strlen($text)-$from;
		}
		return PLANMYDAY_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('planmyday_strtolower')) {
	function planmyday_strtolower($text) {
		return PLANMYDAY_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('planmyday_strtoupper')) {
	function planmyday_strtoupper($text) {
		return PLANMYDAY_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('planmyday_strtoproper')) {
	function planmyday_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<planmyday_strlen($text); $i++) {
			$ch = planmyday_substr($text, $i, 1);
			$rez .= planmyday_strpos(' .,:;?!()[]{}+=', $last)!==false ? planmyday_strtoupper($ch) : planmyday_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('planmyday_strrepeat')) {
	function planmyday_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('planmyday_strshort')) {
	function planmyday_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= planmyday_strlen($str)) 
			return strip_tags($str);
		$str = planmyday_substr(strip_tags($str), 0, $maxlength - planmyday_strlen($add));
		$ch = planmyday_substr($str, $maxlength - planmyday_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = planmyday_strlen($str) - 1; $i > 0; $i--)
				if (planmyday_substr($str, $i, 1) == ' ') break;
			$str = trim(planmyday_substr($str, 0, $i));
		}
		if (!empty($str) && planmyday_strpos(',.:;-', planmyday_substr($str, -1))!==false) $str = planmyday_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('planmyday_strclear')) {
	function planmyday_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (planmyday_substr($text, 0, planmyday_strlen($open))==$open) {
					$pos = planmyday_strpos($text, '>');
					if ($pos!==false) $text = planmyday_substr($text, $pos+1);
				}
				if (planmyday_substr($text, -planmyday_strlen($close))==$close) $text = planmyday_substr($text, 0, planmyday_strlen($text) - planmyday_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('planmyday_get_slug')) {
	function planmyday_get_slug($title) {
		return planmyday_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('planmyday_strmacros')) {
	function planmyday_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('planmyday_unserialize')) {
	function planmyday_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>