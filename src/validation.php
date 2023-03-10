<?php

$GLOBALS['CONFIGURATION_ISSUES'] = [];


/**
 * This function gets a value and returns string form of it
 * It is used to show some non string values in error messages
 * 
 * @param mixed $value
 * 
 * @return string
 */
function convert_to_string($value): string
{
    return (string) $value;
}


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
            $result = handle_single_check(is_string($command_name), 'Command key "' . convert_to_string($command_name) . '" should be a string') && $result;
            $result = handle_single_check(is_array($command), 'Command options for "' . convert_to_string($command_name) . '" should be an array') && $result;
            if (is_array($command)) {
                $result = handle_single_check(isset($command['command']) && is_string($command['command']), 'Option "command" for command "' . convert_to_string($command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['working_dir']) && is_string($command['working_dir']), 'Option "working_dir" for command "' . convert_to_string($command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['stdin_file']) && is_string($command['stdin_file']), 'Option "stdin_file" for command "' . convert_to_string($command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['log_file']) && is_string($command['log_file']), 'Option "log_file" for command "' . convert_to_string($command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['log_tail_maximum_lines']) && is_integer($command['log_tail_maximum_lines']), 'Option "log_tail_maximum_lines" for command "' . convert_to_string($command_name) . '" is not set or is not an integer') && $result;
                $result = handle_single_check(isset($command['process_id_file']) && is_string($command['process_id_file']), 'Option "process_id_file" for command "' . convert_to_string($command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['kill_signal']) && is_string($command['kill_signal']), 'Option "kill_signal" for command "' . convert_to_string($command_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($command['description']) && is_string($command['description']), 'Option "description" for command "' . convert_to_string($command_name) . '" is not set or is not a string. If you don\'t want to set a description for the command, just put a blank "" string in it') && $result;
                $result = handle_single_check(isset($command['custom_actions']) && is_array($command['custom_actions']), 'Option "custom_actions" for command "' . convert_to_string($command_name) . '" is not set or is not an array. If you don\'t want to set any custom action for the command, just put an empty [] array in it') && $result;

                if (isset($command['custom_actions']) && is_array($command['custom_actions'])) {
                    foreach ($command['custom_actions'] as $custom_action) {
                        $result = handle_single_check(is_string($custom_action), 'Item "' . convert_to_string($custom_action) . '" in command "'.convert_to_string($command_name).'"\'s "custom_actions" is not a string') && $result;
                    }
                }
            }
        }
    }

    if (defined('USERS') && is_array(USERS)) {
        foreach (USERS as $user_name => $user) {
            $result = handle_single_check(is_string($user_name), 'Key "' . convert_to_string($user_name) . '" in USERS should be a string') && $result;
            $result = handle_single_check(is_array($user), 'User options for "' . convert_to_string($user_name) . '" should be an array') && $result;
            if (is_array($user)) {
                $result = handle_single_check(isset($user['password']) && is_string($user['password']), 'Option "password" for user "' . convert_to_string($user_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($user['permissions_for_commands']) && is_array($user['permissions_for_commands']), 'Option "permissions_for_commands" for user "' . convert_to_string($user_name) . '" is not set or is not an array') && $result;

                if (is_array($user['permissions_for_commands'])) {
                    foreach ($user['permissions_for_commands'] as $cmd_permission_name => $permissions) {
                        $result = handle_single_check(is_string($cmd_permission_name), 'Key "'.convert_to_string($cmd_permission_name).'" in user "'.convert_to_string($user_name).'"\'s "permissions_for_commands" is not a string') && $result;
                        $result = handle_single_check(is_array($permissions), 'Value of key "'.convert_to_string($cmd_permission_name).'" in user "'.convert_to_string($user_name).'"\'s "permissions_for_commands" is not an array') && $result;
                        if (is_array($permissions)) {
                            foreach ($permissions as $permission) {
                                $result = handle_single_check(is_integer($permission), 'Item "'.convert_to_string($permission).'" in user "'.convert_to_string($user_name).'"\'s "permissions_for_commands" "'.convert_to_string($cmd_permission_name).'" is not a numeric value') && $result;
                            }
                        }
                    }
                }
            }
        }
    }

    $result = handle_single_check(isset($GLOBALS['CUSTOM_ACTIONS']), 'Global variable "CUSTOM_ACTIONS" is not defined') && $result;
    $result = handle_single_check(isset($GLOBALS['CUSTOM_ACTIONS']) && is_array($GLOBALS['CUSTOM_ACTIONS']), 'Global variable "CUSTOM_ACTIONS" should be an array') && $result;

    if (isset($GLOBALS['CUSTOM_ACTIONS']) && is_array($GLOBALS['CUSTOM_ACTIONS'])) {
        foreach ($GLOBALS['CUSTOM_ACTIONS'] as $action_name => $action) {
            $result = handle_single_check(is_string($action_name), 'Key "' . convert_to_string($action_name) . '" in CUSTOM_ACTIONS should be a string') && $result;
            $result = handle_single_check(is_array($action), 'Action options for "' . convert_to_string($action_name) . '" should be an array') && $result;

            if (is_array($action)) {
                $result = handle_single_check(isset($action['title']) && is_string($action['title']), 'Option "title" for custom action "' . convert_to_string($action_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($action['description']) && is_string($action['description']), 'Option "description" for custom action "' . convert_to_string($action_name) . '" is not set or is not a string. You can leave it as a blank "" string') && $result;
                $result = handle_single_check(isset($action['button_color']) && is_string($action['button_color']), 'Option "button_color" for custom action "' . convert_to_string($action_name) . '" is not set or is not a string') && $result;
                $result = handle_single_check(isset($action['is_visible']) && is_callable($action['is_visible']), 'Option "is_visible" for custom action "' . convert_to_string($action_name) . '" is not set or is not a callable') && $result;
                $result = handle_single_check(isset($action['is_enabled']) && is_callable($action['is_enabled']), 'Option "is_enabled" for custom action "' . convert_to_string($action_name) . '" is not set or is not a callable') && $result;
                $result = handle_single_check(isset($action['handle']) && is_callable($action['handle']), 'Option "handle" for custom action "' . convert_to_string($action_name) . '" is not set or is not a callable') && $result;
                $result = handle_single_check(isset($action['parameters']) && is_array($action['parameters']), 'Option "parameters" for custom action "' . convert_to_string($action_name) . '" is not set or is not an array') && $result;

                if (isset($action['parameters']) && is_array($action['parameters'])) {
                    foreach ($action['parameters'] as $k => $v) {
                        $result = handle_single_check(is_string($k) && is_string($v), 'Item "'.convert_to_string($k).'" and it\'s value in "parameters" for custom action "' . convert_to_string($action_name) . '" should be strings') && $result;
                    }
                }
            }
        }
    }

    return $result;
}
