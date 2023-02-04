<?php

function cx_form_input_sanitized($name) {
	if (array_key_exists($name, $_POST)) {
		$insecure_input = $_POST[$name];
		$tagless_input = strip_tags($insecure_input);
		return $tagless_input;
	} else {
		return null;
	}
}
