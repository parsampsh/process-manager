<?php


session_start();


/**
 * This function checks the configs and checks if there is any user implemented or not
 * If these is no item in USERS constant, this means that the app doesn't need authentication
 * 
 * @return bool
 */
function is_there_any_user(): bool
{
    return count(USERS) > 0;
}


/**
 * This function gets GET parameters as an array and attempts the login
 * If it return false, it means attempt failed and wrong credentials
 * But if it returns a string, it means login successful
 * And the returned string is username of the authenticated person
 * 
 * @param array $data
 * 
 * @return bool|string
 */
function attempt_login(array $data = []): bool|string
{
    if ($data === []) {
        $data = $_GET;
    }

    if (!isset($data['username']) || !isset($data['password'])) {
        if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
            $data['username'] = $_SESSION['username'];
            $data['password'] = $_SESSION['password'];
        } else {
            return false;
        }
    }

    if (!isset(USERS[$data['username']])) {
        // wrong username
        return false;
    }

    if (USERS[$data['username']]['password'] !== $data['password']) {
        // wrong password
        return false;
    }

    $_SESSION['username'] = $data['username'];
    $_SESSION['password'] = $data['password'];

    // valid credentials and successful login
    return $data['username'];
}


/**
 * This gets a username and a permission code and checks if that user has that permission
 * And returns a boolean
 * 
 * @param string $username
 * @param int $permission
 * 
 * @return bool
 */
function has_permission($username, int $permission): bool
{
    if ($username === false) {
        // no user is required
        // so all permissions are enable
        return true;
    }

    if (!isset(USERS[$username])) {
        return false;
    }

    if (!isset(USERS[$username]['permissions_for_commands'][get_current_selected_command()['name']])) {
        return false;
    }

    return in_array($permission, USERS[$username]['permissions_for_commands'][get_current_selected_command()['name']]);
}


/**
 * It checks if the current logged in user has a permission
 * 
 * @param int $permission
 * 
 * @return bool
 */
function user_has_permission(int $permission): bool
{
    return has_permission(attempt_login(), $permission);
}


/**
 * This function handles checking password and showing the logic form
 * 
 * @return void
 */
function authentication(): void
{
    if (is_there_any_user()) {
        // then the authentication is required
        if (attempt_login($_GET)) {
            // authorized
            if (isset($_GET['username']) || isset($_GET['password'])) {
                header('Location: ' . explode('?', get_current_url())[0]);
                die();
            }
        } else {
            if (isset($_GET['logout'])) {
                $_SESSION['username'] = null;
                $_SESSION['password'] = null;
            }
            // check if there has been an attempt for login so we should show error
            if (isset($_GET['attempt_login'])) {
                $alert_text = "Invalid username or password";
                $alert_color = "red";
                require_once __DIR__ . '/views/alert.php';
            }
            // show the login form
            require_once __DIR__ . '/views/login_form.php';
            require_once __DIR__ . '/views/foot.php';
            die();
        }
    }
}


/**
 * This function gets a username and a text to log for that user
 * It also adds the current datetime to the log content
 * The logs will go to file USER_LOGS_FILE in settings
 * 
 * @param string|null $username
 * @param string $log
 * 
 * @return void
 */
function make_log_entry($username, string $log): void
{
    $content = PHP_EOL . '[' . date('Y-m-d H:i:s') . '] [User ' . $username . '] [Command ' . get_current_selected_command()['name'] . '] ' . $log;
    $file = fopen(USER_LOGS_FILE, 'a');
    fwrite($file, $content);
    fclose($file);
}


/**
 * This function makes a log entry for the current authenticated user
 * 
 * @param string $log
 * 
 * @return void
 */
function user_make_log_entry(string $log): void
{
    make_log_entry(attempt_login(), $log);
}

const PERMISSION_READ_LOG = 1;
const PERMISSION_READ_STATS = 2;
const PERMISSION_START = 3;
const PERMISSION_STOP = 4;

const PERMISSION_ALL = [
    PERMISSION_READ_LOG,
    PERMISSION_READ_STATS,
    PERMISSION_START,
    PERMISSION_STOP,
];
