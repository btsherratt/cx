<?php

/*
 ,-----,--.   ,--.    ,-----. ,--.
'  .--./\  `.'  /     |  |) /_|  |,---. ,---.,--.   ,--.,--,--,--.--.,---.
|  |     .'    \      |  .-.  |  | .-. | .-. |  |.'.|  ' ,-.  |  .--| .-. :
'  '--'\/  .'.  \     |  '--' |  ' '-' ' '-' |   .'.   \ '-'  |  |  \   --.
 `-----'--'   '--'    `------'`--'`---'.`-  /'--'   '--'`--`--`--'   `----'
                                       `---'
 */

define('CX_PHP_VERSION', '8.0.0');

if (version_compare(phpversion(), CX_PHP_VERSION) < 0) {
    echo('Requires PHP version ' . CX_PHP_VERSION . ' or later');
    exit;
}

$main_file = join(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'cx', 'cx.php'));
$db_file = join(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'db', 'btscx.db'));
$data_folder = join(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'data'));
$public_data_folder = join(DIRECTORY_SEPARATOR, array(__DIR__, 'data'));
require_once($main_file);
cx($db_file, $data_folder, $public_data_folder);
