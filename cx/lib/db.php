<?php

function _cx_db() {
	static $db = new SQLite3(CX_DATABASE_FILE);
	return $db;
}

function _cx_db_sql_exec($sql, ...$args) {
	$db = _cx_db();
	$statement = $db->prepare($sql);

	foreach ($args as $i => $arg) {
		$idx = $i + 1;
		$statement->bindValue($idx, $arg);
	}

	return $statement->execute();
}

function cx_db_exec($sql, ...$args) {
	_cx_db_sql_exec($sql, ...$args);
	return _cx_db()->lastInsertRowID();
}

function cx_db_query($sql, ...$args) {
	$result_set = _cx_db_sql_exec($sql, ...$args);
	while ($result = $result_set->fetchArray()) {
		yield $result;
	}
}
