<?php


/**
 * It returns if the current operating system is a UNIX based OS
 * If it returns false, it probably means that the OS is Windows
 *
 * @return bool
 */
function is_unix(): bool
{
    return strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN';
}


/**
 * This command handles running the command in different operating systems
 *
 * @param string $command
 * @param string $outputFile
 *
 * @return int
 */
function os_shell_exec(string $command, string $outputFile = '/dev/null'): int
{
    if (is_unix()) {
        return    unix_shell_exec($command, $outputFile);
    } else {
        return win_shell_exec($command, $outputFile);
    }
}


/**
 * Runs the command in UNIX OS
 *
 * @param string $command
 * @param string $outputFile
 *
 * @return int
 */
function unix_shell_exec(string $command, string $outputFile = '/dev/null'): int
{
    return shell_exec(sprintf(
        '%s > %s 2>&1 & echo $!',
        $command,
        $outputFile,
    ));
}


/**
 * Runs the command in Windows OS
 *
 * @param string $command
 * @param string $outputFile
 *
 * @return int
 */
function win_shell_exec(string $command, string $outputFile = '/dev/null'): int
{
    return 0; // TODO : write this function
}


/**
 * Checks if a process exists by the process ID in different operating systems
 *
 * @param int $processID
 *
 * @return bool
 */
function os_process_exists(int $processID): bool
{
    if (is_unix()) {
        return    unix_process_exists($processID);
    } else {
        return win_process_exists($processID);
    }
}


/**
 * Checks if process exists on UNIX OS
 *
 * @param int $processID
 *
 * @return bool
 */
function unix_process_exists(int $processID): bool
{
    return file_exists("/proc/$processID");
}


/**
 * Checks if process exists on Windows OS
 *
 * @param int $processID
 *
 * @return int
 */
function win_process_exists(int $processID): bool
{
    return true; // TODO : write this function
}


/**
 * Kills a process in different operating systems
 *
 * @param string $signal
 * @param int $processID
 *
 * @param void
 */
function os_kill_process(string $signal, int $processID): void
{
    if (is_unix()) {
        unix_kill_process($signal, $processID);
    } else {
        win_kill_process($signal, $processID);
    }
}


/**
 * Kills a process in UNIX OS
 *
 * @param string $signal
 * @param int $processID
 *
 * @return void
 */
function unix_kill_process(string $signal, int $processID): void
{
    exec('kill -' . $signal . ' ' . $processID);
}


/**
 * Kills a process in Windows
 *
 * @param string $signal
 * @param int $processID
 *
 * @return void
 */
function win_kill_process(string $signal, int $processID): void
{
    // TODO : write this function
}


/**
 * Tails a file in different operating systems
 *
 * @param int $linesCount
 * @param string $file
 *
 * @return string
 */
function os_tail_file(int $linesCount, string $file)
{
    if (is_unix()) {
        return unix_tail_file($linesCount, $file);
    } else {
        return win_tail_file($linesCount, $file);
    }
}


/**
 * Tails a file in UNIX
 *
 * @param int $linesCount
 * @param string $file
 *
 * @param string
 */
function unix_tail_file(int $linesCount, string $file)
{
    return shell_exec('tail -n ' . $linesCount . ' ' . $file);
}


/**
 * Tails a file in Windows
 *
 * @param int $linesCount
 * @param string $file
 *
 * @param string
 */
function win_tail_file(int $linesCount, string $file)
{
    return ''; // TODO : write this function
}
