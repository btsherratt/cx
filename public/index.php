<?php

/*
 ,-----,--.   ,--.    ,-----. ,--.
'  .--./\  `.'  /     |  |) /_|  |,---. ,---.,--.   ,--.,--,--,--.--.,---.
|  |     .'    \      |  .-.  |  | .-. | .-. |  |.'.|  ' ,-.  |  .--| .-. :
'  '--'\/  .'.  \     |  '--' |  ' '-' ' '-' |   .'.   \ '-'  |  |  \   --.
 `-----'--'   '--'    `------'`--'`---'.`-  /'--'   '--'`--`--`--'   `----'
                                       `---'
 */

$main_file = join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "cx", "cx.php"));
$db_file = join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "db", "btscx.db"));
$data_folder = join(DIRECTORY_SEPARATOR, array(__DIR__, "..", "data"));
$public_data_folder = join(DIRECTORY_SEPARATOR, array(__DIR__, "data"));
require_once($main_file);
cx($db_file, $data_folder, $public_data_folder);
