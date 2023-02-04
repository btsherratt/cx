<?php

cx_require('lib', 'db.php');
cx_require('lib', 'setup.php');

function cx_system_get_metadata($key, $default_value = null) {

}

function cx_system_set_metadata($key, $value) {
	
}

cx_setup_register(1, function() {
	cx_db_exec('CREATE TABLE system_metadata (
			system_metadata_key STRING PRIMARY KEY,
			system_metadata_value STRING
		);');
});
