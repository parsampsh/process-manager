        </div>

        <footer class="footer">
            Powered by <a target="blank" href="https://github.com/parsampsh/process-manager">Process Manager</a>
            <br />
            (c) 2022-2023 Parsa Shahmaleki and contributors
        </footer>

        <?php if (attempt_login() !== false) { ?>
            <div class="logged-in-as">
                <span>
                    Logged in as <b><?= attempt_login() ?></b>
                    <a class="button red-button" href="?username=&password=&logout=1">Logout</a>
                </span>
            </div>
        <?php } ?>
    
        <script>
            var alert_closes = document.querySelectorAll('.alert-close');
            if (alert_closes.length === undefined) {
                alert_closes = [alert_closes];
            }
            for (var i = 0; i < alert_closes.length; i++) {
                alert_closes[i].addEventListener('click', function (event) {
                    event.target.parentNode.remove();
                });
            }

            function action_handle_params()
            {
                var actions_sections = document.querySelectorAll('.action-section');
                for (var i = 0; i < actions_sections.length; i++) {
                    actions_sections[i].style.display = 'none';
                    actions_sections[i].classList.remove('action-panel');
                }

                var action_params = event.target.parentNode.querySelector('.action-params');
                var current_status = action_params.style.display;

                var actions_params = document.querySelectorAll('.action-params');
                for (var i = 0; i < actions_params.length; i++) {
                    actions_params[i].style.display = 'none';
                }

                event.target.parentNode.style.display = 'inline-block';
                event.target.parentNode.classList.add('action-panel');

                if (current_status != 'block') {
                    action_params.style.display = 'block';
                    event.preventDefault();
                    event.stopPropagation();
                }
            }

            function action_close()
            {
                var actions_sections = document.querySelectorAll('.action-section');
                for (var i = 0; i < actions_sections.length; i++) {
                    actions_sections[i].style.display = 'inline';
                    actions_sections[i].classList.remove('action-panel');
                }

                var actions_params = document.querySelectorAll('.action-params');
                for (var i = 0; i < actions_params.length; i++) {
                    actions_params[i].style.display = 'none';
                }
            }

            var logs = document.querySelectorAll(".logs-container");
            for (var i = 0; i < logs.length; i++) {
                logs[i].scrollTop = logs[i].scrollHeight;
            }
        </script>

        <style>
            .yellow-button {
                background-color: yellow;
                color: #111;
            }

            .yellow-button:hover {
                background-color: #F39C12;
            }

            .action-params {
                display: none;
            }

            .action-section {
                display: inline;
            }

            .action-panel {
                padding: 30px;
                background-color: #ccc;
                border-radius: 10px;
                position: relative;
            }

            .action-close {
                top: 10;
                right: 10;
                position: absolute;
                cursor: pointer;
            }
        </style>
    </body>
</html>
