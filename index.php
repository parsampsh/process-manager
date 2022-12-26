<?php

require_once __DIR__ . '/src/core.php';

// load the settings
if (file_exists(__DIR__ . '/settings.php')) {
    require_once __DIR__ . '/settings.php';
} else {
    require_once __DIR__ . '/settings.example.php';
}

require_once __DIR__ . '/src/views/head.php';

// check if configs are valid
handle_config_validation();

// check the password
authentication();

// load the process status
$processID = get_current_process_id();
$isRunning = $processID !== false;


if (isset($_POST['start']) && !$isRunning) {
    start_process();
    refresh_page();
} else if (isset($_POST['stop']) && $isRunning) {
    stop_process();
    refresh_page();
}


// re-load the process status
$processID = get_current_process_id();
$isRunning = $processID !== false;


$logs = load_logs();

?>

<div>
    <form method="GET">
        <input type="hidden" name="username" value="<?= isset($_GET['username']) ? $_GET['username'] : '' ?>" />
        <input type="hidden" name="password" value="<?= isset($_GET['password']) ? $_GET['password'] : '' ?>" />

        <b>Process</b>: <select class="dropdown" onchange="event.target.parentNode.submit()" name="command">
            <?php $currentSelectedCommand = isset($_GET['command']) ? $_GET['command'] : ''; ?>
            <?php foreach (COMMANDS as $command => $options) { ?>
                <option <?= $currentSelectedCommand == $command ? 'selected' : '' ?> value="<?= $command ?>"><?= $command ?></option>
            <?php } ?>
        </select>

        <p><?= get_current_selected_command()['description'] ?></p>
    </form>
</div>

<div>
    <b>Status</b>: <?= $isRunning ? '<span style="color: green;">Running</span>' : '<span style="color: red;">Not running</span>' ?>
    <?php if ($isRunning) { ?>
        <br />
        <b>Current process ID</b>: <?= $processID ?>
    <?php } else { ?>
        <br />
        <br />
    <?php } ?>
</div>

<?php if (user_has_permission(PERMISSION_START) || user_has_permission(PERMISSION_STOP)) { ?>
    <hr />
    <div>
        <h4>Actions</h4>
        <form method="POST">
            <?php if (user_has_permission(PERMISSION_START)) { ?>
                <button class="button green-button" <?= $isRunning ? 'disabled' : '' ?> type="submit" name="start">Start</button>
            <?php } ?>

            <?php if (user_has_permission(PERMISSION_STOP)) { ?>
                <button class="button red-button" <?= $isRunning ? '' : 'disabled' ?> type="submit" name="stop">Stop</button>
            <?php } ?>
        </form>
    </div>
<?php } ?>

<?php if (user_has_permission(PERMISSION_READ_STATS)) { ?>
    <hr />
    <div>
        <h4 style="float: left;">Stats</h4>
        <a href="" class="button blue-button" style="float: right; text-decoration: none;">Refresh</a>
        <div style="clear: both;"></div>
        <pre class="logs-container">Coming soon...</pre>
    </div>
<?php } ?>

<?php if (user_has_permission(PERMISSION_READ_LOG)) { ?>
    <hr />
    <div>
        <h4 style="float: left;">Logs</h4>
        <a href="" class="button blue-button" style="float: right; text-decoration: none;">Refresh</a>
        <div style="clear: both;"></div>
        <pre class="logs-container"><?= $logs ?></pre>
    </div>
<?php } ?>

<?php require_once __DIR__ . '/src/views/foot.php'; ?>
