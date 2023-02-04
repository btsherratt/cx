<?php

function cx_http_redirect($url) {
	header('Location: ' . $url, true, 302);
}
