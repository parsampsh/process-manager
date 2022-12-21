<?php


/**
 * This function makes a response with the given json array and status code
 * 
 * @param array $response
 * @param int $statusCode
 * 
 * @return void
 */
function api_response(array $response, int $statusCode = 200): void
{
    http_response_code($statusCode);
    die(json_encode($response));
}


/**
 * This function handles the authentication for the API
 * 
 * @return void
 */
function api_authentication(): void
{
    if (!is_there_any_user()) {
        return;
    }

    $result = attempt_login($_GET);

    if ($result === false) {
        api_response([
            'ok' => false,
            'message' => 'Failed to authenticate',
        ], 401);
    }
}
