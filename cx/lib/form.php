<?php

function cx_form_input_sanitized($name) {
	$input = cx_form_input_sanitized_allowing_html($name);

	if ($input != null) {
		$input = strip_tags($input);
	}
	
	return $input;
}

function cx_form_input_sanitized_allowing_html($name) {
	if (array_key_exists($name, $_POST)) {
		$insecure_input = $_POST[$name];
		return $insecure_input;
	} else {
		return null;
	}
}

function cx_form_input_sanitized_date_time($name) {
	$sanitised_string = cx_form_input_sanitized($name);

	if ($sanitised_string != null) {
		$date = date_parse_from_format('Y-m-d H:i:s', $sanitised_string);
		if ($date['error_count'] > 0) $date = date_parse_from_format('Y-m-d', $sanitised_string);
		if ($date['error_count'] == 0) {
			$timestamp = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
			if ($timestamp != false) {
				return $timestamp;
			}
		}
	}

	return null;
}
