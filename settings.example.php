<?php


// You can set the users here. Put the username in array key, in in each you can set password and permissions
// there are 4 permissions: PERMISSION_READ_LOG, PERMISSION_READ_STATS, PERMISSION_START, PERMISSION_STOP
// You can set permissions of the user to each command like this:
// You can put them in an array like this:
// 'permissions_for_commands' => [
//    'Main' => [PERMISSION_READ_LOG, PERMISSION_READ_STATS],
// ],
// Also if you want to give someone all of the permissions, you don't need to write them all.
// You can write it this way: 'Main' => PERMISSION_ALL
const USERS = [
    /*'admin' => [
        'password' => 'admin',
        'permissions_for_commands' => [
            'Main' => PERMISSION_ALL,
            'Second' => [
                PERMISSION_READ_LOG,
            ],
        ],
    ],*/
];


// The user logs will be saved into this file
// The logs include datetime, username and the action happened
// For example it says user Admin has started the process at datetime X
const USER_LOGS_FILE = 'user-logs.txt';


// You can implement multiple commands here
// User can select which command to manage in a dropdown in the main page of the app
const COMMANDS = [
    'Main' => [
        'command' => 'python3 test-script.py', // the command to run
        'working_dir' => __DIR__, // working directory of the command
        'log_file' => __DIR__ . '/log-file.txt', // a file to log command output to it
        'log_tail_maximum_lines' => 20, // number of the lines for the log file tail when we show the logs
        'process_id_file' => 'process-id.txt', // a file to store the process id for the command
        'kill_signal' => 'TERM', // the signal you want to be sent to the command when the stop button gets pressed
    ],
    'Second' => [
        'command' => 'python3 test-script.py',
        'working_dir' => __DIR__,
        'log_file' => __DIR__ . '/log-file-2.txt',
        'log_tail_maximum_lines' => 20,
        'process_id_file' => 'process-id-2.txt',
        'kill_signal' => 'TERM',
    ],
];
