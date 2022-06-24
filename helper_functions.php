<?php
/**
 * Function for checking if needle is in array, but case insensitive.
 */
function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

/**
 * Function for sorting emailadresses.
 */
function cmp_email($a, $b)
{
	if (strtolower(substr($a,0,13))=="nietingedeeld") {
	  return 1;
	}
	if( substr($b,0,13)=="nietingedeeld") {
	  return -1;
	}
	return strcmp($a, $b);
}

/**
 * Function for sorting emailadresses in a sub array.
 */
function cmp_email_array($a, $b)
{
	if($a[0] == $b[0]){
		return strcmp($a[1], $b[1]);
	}
	if (strtolower(substr($a[0],0,13))=="nietingedeeld") {
	  return 1;
	}
	if( substr($b[0],0,13)=="nietingedeeld") {
	  return -1;
	}
	return strcmp($a[0], $b[0]);
}