<?php
// is server under SSL?
$isSecure=false;
if ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) {
	$isSecure = true;
} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty( $_SERVER['HTTP_X_FORWARDED_SSL'] ) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on' ) {
	$isSecure = true;
}

define('PROTOCOL', $isSecure ? 'https' : 'http');
define('HOST', $_SERVER['HTTP_HOST']);