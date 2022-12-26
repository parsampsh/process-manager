<?php


require_once __DIR__ . '/os.php';
require_once __DIR__ . '/validation.php';



/**
 * This function handles showing an error if configurations are not valid
 * 
 * @return void
 */
function handle_config_validation()
{
    if (!validate_configuration()) {
        $alert_text = "ERROR: The configurations are invalid<br />Solution: Edit file " . realpath(__DIR__ . '/../') . '/settings.php';
        $alert_color = "red";
        $alert_text .= '<ul>';
        foreach ($GLOBALS['CONFIGURATION_ISSUES'] as $issue) {
            $alert_text .= '<li>' . $issue . '</li>';
        }
        $alert_text .= '</ul>';
        $alert_text .= 'Check out <a target="blank" href="https://github.com/parsampsh/process-manager#configuration">process manager documentation</a> for config instructions';
        require_once __DIR__ . '/views/alert.php';
        die();
        require_once __DIR__ . '/views/foot.php';
    }
}


/**
 * This function returns the options of the current selected command
 * 
 * @return array
 */
function get_current_selected_command(): array
{
    if (!isset($_GET['command'])) {
        $commandName = array_key_first(COMMANDS);
    } else {
        $commandName = $_GET['command'];
    }

    if (!isset(COMMANDS[$commandName])) {
        $commandName = array_key_first(COMMANDS);
    }

    $options = COMMANDS[$commandName];
    $options['name'] = $commandName;

    return $options;
}


/**
 * This function receives a command and an output file
 * Then runs this command in the background
 * And returns the process ID of the created process
 * 
 * @param string $command
 * @param string $outputFile
 * 
 * @return int
 */
function run_command_in_bg(string $command, string $outputFile = '/dev/null'): int
{
    $original_working_dir = getcwd();
    chdir(get_current_selected_command()['working_dir']);
    $pid = os_shell_exec($command, $outputFile);
    chdir($original_working_dir);
    return $pid;
}


/**
 * This function receives an integer as the process ID and saves it
 * The saved ID can be loaded later by `get_current_process_id`
 * 
 * @param int $processID
 * 
 * @return void
 */
function save_ran_process_id(int $processID): void
{
    $file = fopen(get_current_selected_command()['process_id_file'], 'w');
    fwrite($file, $processID);
    fclose($file);
}


/**
 * This command returns the current running process ID
 * If the process is not running, then it returns false
 * 
 * @return int|bool
 */
function get_current_process_id(): int|bool
{
    touch(get_current_selected_command()['process_id_file']);
    $file = fopen(get_current_selected_command()['process_id_file'], 'r');
    $content = fread($file, filesize(get_current_selected_command()['process_id_file']) + 1);
    fclose($file);

    $processID = intval($content);

    if ($processID === 0) {
        return false;
    }

    // check if the process is still running
    if (!os_process_exists($processID)) {
        return false;
    }

    return $processID;
}


/**
 * Runs the process
 * 
 * @return void
 */
function start_process(): void
{
    if (!user_has_permission(PERMISSION_START)) {
        return;
    }

    user_make_log_entry('Started the process');

    $processID = run_command_in_bg(get_current_selected_command()['command'], get_current_selected_command()['log_file']);
    save_ran_process_id($processID);
}


/**
 * Stops the process
 * It receives the process ID to stop
 * 
 * @return void
 */
function stop_process(): void
{
    if (!user_has_permission(PERMISSION_STOP)) {
        return;
    }

    user_make_log_entry('Stopped the process');

    os_kill_process(get_current_selected_command()['kill_signal'], get_current_process_id());
    while (get_current_process_id() !== false) {
        // just wait until process stops...
    }
}


/**
 * This function loads the logs from the log file
 * 
 * @return string
 */
function load_logs(): string|null
{
    if (!user_has_permission(PERMISSION_READ_LOG)) {
        return null;
    }

    user_make_log_entry('Read the process logs');

    touch(get_current_selected_command()['log_file']);
    return os_tail_file(get_current_selected_command()['log_tail_maximum_lines'], get_current_selected_command()['log_file']);
}


/**
 * Returns the current page URL
 *
 * @return string
 */
function get_current_url(): string
{
    $url = '';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }

    $url.= $_SERVER['HTTP_HOST'];
    $url.= $_SERVER['REQUEST_URI'];

    return $url;
}


/**
 * This function technically redirects user to the current URL
 * It's used to clear the POST arguments
 *
 * @return void
 */
function refresh_page(): void
{
    header('Location: ' . get_current_url());
}


require_once __DIR__ . '/authentication.php';
