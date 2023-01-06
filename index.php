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
} else {
    foreach (get_custom_actions() as $action => $options) {
        if (isset($_POST[$action])) {
            if (is_custom_action_doable($action)) {
                run_custom_action($action);
                refresh_page();
            }
        }
    }
}


// re-load the process status
$processID = get_current_process_id();
$isRunning = $processID !== false;


$logs = load_logs();

?>

<div>
    <form method="GET">
        <?php if (count(COMMANDS) > 1) { ?>
            <b>Process</b>: <select class="dropdown" onchange="event.target.parentNode.submit()" name="command">
                <?php $currentSelectedCommand = isset($_GET['command']) ? $_GET['command'] : ''; ?>
                <?php foreach (COMMANDS as $command => $options) { ?>
                    <option <?= $currentSelectedCommand == $command ? 'selected' : '' ?> value="<?= $command ?>"><?= $command ?></option>
                <?php } ?>
            </select>
        <?php } else { ?>
            <b><?= get_current_selected_command()['name'] ?></b>
        <?php } ?>

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

<?php if (user_has_permission(PERMISSION_START) || user_has_permission(PERMISSION_STOP) || is_there_any_visible_custom_action()) { ?>
    <hr />
    <div>
        <h4>Actions</h4>
        <form method="POST">
            <?php if (user_has_permission(PERMISSION_START)) { ?>
                <div class="action-section">
                    <button class="button green-button" <?= $isRunning ? 'disabled' : '' ?> type="submit" name="start">Start</button>
                </div>
            <?php } ?>

            <?php if (user_has_permission(PERMISSION_STOP)) { ?>
                <div class="action-section">
                    <button class="button red-button" <?= $isRunning ? '' : 'disabled' ?> type="submit" name="stop">Stop</button>
                </div>
            <?php } ?>

            <?php foreach (get_custom_actions() as $action => $options) { ?>
                <?php if (is_custom_action_visible($action)) { ?>
                    <div class="action-section">
                        <?php if (count($options['parameters']) > 0 && is_custom_action_enabled($action)) { ?>
                            <div class="action-params action-<?= $action ?>-params" id="action_<?= $action ?>_params">
                                <span onclick="action_close()" class="action-close">X</span>
                                <?php foreach ($options['parameters'] as $param => $description) { ?>
                                    <?= $description ?>: <input class="text-input" placeholder="<?= $description ?>" type="text" name="param_<?= $action ?>_<?= $param ?>" />
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <button <?= count($options['parameters']) > 0 ? 'onclick="action_handle_params()"' : '' ?> title="<?= $options['description'] ?>" class="button <?= $options['button_color'] ?>-button" <?= is_custom_action_enabled($action) ? '' : 'disabled' ?> type="submit" name="<?= $action ?>"><?= $options['title'] ?></button>
                    </div>
                <?php } ?>
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
        <pre class="logs-container"><?= htmlspecialchars($logs) ?></pre>
    </div>
<?php } ?>

<?php require_once __DIR__ . '/src/views/foot.php'; ?>
