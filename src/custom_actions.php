<?php


/**
 * This function returns the custom actions list
 * 
 * @return array
 */
function get_custom_actions(): array
{
    $result = [];

    foreach ($GLOBALS['CUSTOM_ACTIONS'] as $name => $options) {
        $options['name'] = $name;
        $result[$name] = $options;
    }

    return $result;
}


/**
 * This function gets a custom action name and checks if user has permission to do it or not
 * 
 * @param string $action_name
 * 
 * @return bool
 */
function is_custom_action_visible(string $action_name): bool
{
    if (!isset(get_custom_actions()[$action_name])) {
        return false;
    }

    if (!in_array($action_name, get_current_selected_command()['custom_actions'])) {
        return false;
    }

    $callable = get_custom_actions()[$action_name]['is_visible'];
    return (bool) call_user_func_array($callable, [get_current_process_id()]);
}


/**
 * This function checks if there is at least 1 visible custom action
 * 
 * @return bool
 */
function is_there_any_visible_custom_action(): bool
{
    foreach (get_custom_actions() as $command => $options) {
        if (is_custom_action_visible($command)) {
            return true;
        }
    }

    return false;
}

/**
 * This function gets a custom action name and checks if it is enabled or not
 * 
 * @param string $action_name
 * 
 * @return bool
 */
function is_custom_action_enabled(string $action_name): bool
{
    if (!isset(get_custom_actions()[$action_name])) {
        return false;
    }

    $callable = get_custom_actions()[$action_name]['is_enabled'];
    return (bool) call_user_func_array($callable, [get_current_process_id()]);
}


/**
 * This function gets a custom action name and checks if it is doable or not
 * If it returns false, then action should not be ran
 * One reason can be that user doesn't have permission
 * Other can be that the action is disabled
 * 
 * @param string $action_name
 * 
 * @return bool
 */
function is_custom_action_doable(string $action_name): bool
{
    return is_custom_action_visible($action_name) && is_custom_action_enabled($action_name);
}


/**
 * This function get a custom action name and runs it
 * 
 * @param string $action_name
 */
function run_custom_action(string $action_name)
{
    if (!isset(get_custom_actions()[$action_name])) {
        return false;
    }

    // load parameters
    $params = [];
    foreach (get_custom_actions()[$action_name]['parameters'] as $k => $v) {
        if (isset($_POST['param_'.$action_name.'_'.$k])) {
            $params[$k] = $_POST['param_'.$action_name.'_'.$k];
        } else if (isset($_GET['param_'.$action_name.'_'.$k])) {
            $params[$k] = $_GET['param_'.$action_name.'_'.$k];
        } else {
            $params[$k] = null;
        }
    }

    $callable = get_custom_actions()[$action_name]['handle'];

    return call_user_func_array($callable, [get_current_process_id(), $params]);
}


const PERMISSION_BUILTIN_ACTION_ENTER_INPUT = 24654765;


/**
 * Registers the builtin custom actions
 * 
 * @return void
 */
function register_builtin_custom_actions(): void
{
    if (!isset($GLOBALS['CUSTOM_ACTIONS']['enter_input'])) {
        $GLOBALS['CUSTOM_ACTIONS']['enter_input'] = [
            'title' => 'Enter input',
            'description' => 'Enters an input into process STDIN',
            'button_color' => 'blue',
            'is_visible' => (function () {
                return user_has_permission(PERMISSION_BUILTIN_ACTION_ENTER_INPUT);
            }),
            'is_enabled' => (function ($processID) {
                return $processID !== false;
            }),
            'parameters' => [
                'input' => 'Enter the input',
            ],
            'handle' => (function ($processID, $params) {
                $input = $params['input'];
                $stdin_file = fopen(get_current_selected_command()['stdin_file'], 'a');
                fwrite($stdin_file, $input.PHP_EOL);
                fclose($stdin_file);
            }),
        ];
    }
}
