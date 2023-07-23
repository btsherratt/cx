<?php

cx_require('lib', 'site.php');

function cx_url($path) {
	return $path;
}

function cx_url_admin($path) {
	return cx_url('/cx' . $path);
}

function cx_url_site($path) {
	return 'https://' . cx_site_host() . cx_url($path);
}
