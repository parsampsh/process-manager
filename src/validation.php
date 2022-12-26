<?php

$GLOBALS['CONFIGURATION_ISSUES'] = [];


/**
 * This function is used to handle one single check while validating configs
 * 
 * @param bool $check
 * @param string $reason
 * 
 * @return bool
 */
function handle_single_check(bool $check, string $reason): bool {
    if (!$check) {
        array_push($GLOBALS['CONFIGURATION_ISSUES'], $reason);
    }

    return $check;
}


/**
 * This function checks all of the configuration options and makes sure they are valid
 * 
 * @return bool
 */
function validate_configuration(): bool
{
    $result = true;

    $result = handle_single_check(defined('USER_LOGS_FILE'), 'Const "USER_LOGS_FILE" is not defined') && $result;
    $result = handle_single_check(defined('USER_LOGS_FILE') && is_string(USER_LOGS_FILE), 'Const "USER_LOGS_FILE" should be a string') && $result;
    $result = handle_single_check(defined('USERS'), 'Const "USERS" is not defined') && $result;
    $result = handle_single_check(defined('USERS') && is_array(USERS), 'Const "USERS" should be an array') && $result;
    $result = handle_single_check(defined('COMMANDS'), 'Const "COMMANDS" is not defined') && $result;
    $result = handle_single_check(defined('COMMANDS') && is_array(COMMANDS), 'Const "COMMANDS" should be an array') && $result;

    if (defined('COMMANDS') && is_array(COMMANDS)) {
        foreach (COMMANDS as $command_name => $command) {
            $result = handle_single_check(is_string($command_name), 'Command key "' . ((string) $command_name) . '" should be a string') && $result;
            $result = handle_single_check(is_array($command), 'Command options for "' . ((string) $command_name) . '" should be an array') && $result;
            if (is_array($command)) {
                $result = handle_single_check(isset($command['command']) && is_string($command['command']), 'Option "command" for command "' . ((string) $command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['working_dir']) && is_string($command['working_dir']), 'Option "working_dir" for command "' . ((string) $command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['log_file']) && is_string($command['log_file']), 'Option "log_file" for command "' . ((string) $command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['log_tail_maximum_lines']) && is_numeric($command['log_tail_maximum_lines']), 'Option "log_tail_maximum_lines" for command "' . ((string) $command_name) . '" is not set or is not an integer') && $result;
                $result = handle_single_check(isset($command['process_id_file']) && is_string($command['process_id_file']), 'Option "process_id_file" for command "' . ((string) $command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['kill_signal']) && is_string($command['kill_signal']), 'Option "kill_signal" for command "' . ((string) $command_name) . '" is not set or is not a string') && $result;
            }
        }
    }

    if (defined('USERS') && is_array(USERS)) {
        foreach (USERS as $user_name => $user) {
            $result = handle_single_check(is_string($user_name), 'Key "' . ((string) $user_name) . '" in USERS should be a string') && $result;
            $result = handle_single_check(is_array($user), 'User options for "' . ((string) $user_name) . '" should be an array') && $result;
            if (is_array($user)) {
                $result = handle_single_check(isset($user['password']) && is_string($user['password']), 'Option "password" for user "' . ((string) $user_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($user['permissions_for_commands']) && is_array($user['permissions_for_commands']), 'Option "permissions_for_commands" for user "' . ((string) $user_name) . '" is not set or is not an array') && $result;

                if (is_array($user['permissions_for_commands'])) {
                    foreach ($user['permissions_for_commands'] as $cmd_permission_name => $permissions) {
                        $result = handle_single_check(is_string($cmd_permission_name), 'Key "'.((string) $cmd_permission_name).'" in user "'.((string) $user_name).'"\'s "permissions_for_commands" is not a string') && $result;
                        $result = handle_single_check(is_array($permissions), 'Value of key "'.((string) $cmd_permission_name).'" in user "'.((string) $user_name).'"\'s "permissions_for_commands" is not an array') && $result;
                        if (is_array($permissions)) {
                            foreach ($permissions as $permission) {
                                $result = handle_single_check(is_numeric($permission), 'Item "'.((string) $permission).'" in user "'.((string) $user_name).'"\'s "permissions_for_commands" is not a numeric value "' . ((string) $user_name) . '" is not set or is not an array') && $result;
                            }
                        }
                    }
                }
            }
        }
    }

    return $result;
}
