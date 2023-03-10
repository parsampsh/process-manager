<?php

require_once __DIR__ . '/src/core.php';
require_once __DIR__ . '/src/api.php';

// load the settings
if (file_exists(__DIR__ . '/settings.php')) {
    require_once __DIR__ . '/settings.php';
} else {
    require_once __DIR__ . '/settings.example.php';
}

// check if configs are valid
api_handle_config_validation();

// check the password
api_authentication();

$allowedActions = [
    'status',
    'stats',
    'logs',
    'start',
    'stop',
    'commands_list',
];

$allowedActions = array_merge($allowedActions, array_keys(get_custom_actions()));

if (!isset($_GET['action'])) {
    api_response([
        'ok' => false,
        'message' => 'Missing `action` parameter',
    ], 400);
} else {
    if (!in_array($_GET['action'], $allowedActions)) {
        api_response([
            'ok' => false,
            'message' => 'Invalid value for `action` parameter. It should be one of these: ' . join(', ', $allowedActions),
        ], 400);
    }
}

$response = [
    'command_name' => get_current_selected_command()['name'],
    'ok' => true,
];
$responseStatusCode = 200;

$processID = get_current_process_id();
$isRunning = $processID !== false;

if ($_GET['action'] == 'status') {
    $response['is_running'] = $isRunning;
    $response['process_id'] = $processID;
} else if ($_GET['action'] == 'stats') {
    if (!user_has_permission(PERMISSION_READ_STATS)) {
        $response['ok'] = false;
        $response['message'] = 'You don\'t have permission to read the stats for this command';
        $responseStatusCode = 403;
    } else {
        $response['message'] = 'Coming soon...';
        user_make_log_entry('Read process stats');
    }
} else if ($_GET['action'] == 'logs') {
    if (!user_has_permission(PERMISSION_READ_LOG)) {
        $response['ok'] = false;
        $response['message'] = 'You don\'t have permission to read the logs for this command';
        $responseStatusCode = 403;
    } else {
        $response['logs'] = load_logs();
    }
} else if ($_GET['action'] == 'start') {
    if (!user_has_permission(PERMISSION_START)) {
        $response['ok'] = false;
        $response['message'] = 'You don\'t have permission to start this command';
        $responseStatusCode = 403;
    } else {
        if ($isRunning) {
            $response['ok'] = false;
            $response['message'] = 'Process is already running';
            $responseStatusCode = 400;
        } else {
            start_process();
            $response['message'] = 'Process started successfully';
        }
    }
} else if ($_GET['action'] == 'stop') {
    if (!user_has_permission(PERMISSION_STOP)) {
        $response['ok'] = false;
        $response['message'] = 'You don\'t have permission to stop this command';
        $responseStatusCode = 403;
    } else {
        if (!$isRunning) {
            $response['ok'] = false;
            $response['message'] = 'Process is not running';
            $responseStatusCode = 400;
        } else {
            stop_process();
            $response['message'] = 'Process stopped successfully';
        }
    }
} else if ($_GET['action'] == 'commands_list') {
    $response['commands'] = array_keys(COMMANDS);
} else if (isset(get_custom_actions()[$_GET['action']])) {
    if (is_custom_action_visible($_GET['action'])) {
        if (is_custom_action_enabled($_GET['action'])) {
            run_custom_action($_GET['action']);
            $response['message'] = 'Action ran successfully';
        } else {
            $response['ok'] = false;
            $response['message'] = 'This action is not possible';
            $responseStatusCode = 400;
        }
    } else {
        $response['ok'] = false;
        $response['message'] = 'You don\'t have permission to do this action';
        $responseStatusCode = 403;
    }
}

api_response($response, $responseStatusCode);
