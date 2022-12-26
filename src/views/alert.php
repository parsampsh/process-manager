<?php if (isset($alert_text) && isset($alert_color)) { ?>
    <div class="alert <?= $alert_color ?>-alert">
        <span class="alert-close"></span>
        <?= $alert_text ?>
    </div>
<?php } ?>
