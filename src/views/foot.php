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
                    <a class="button red-button" href="?username=&password=">Logout</a>
                </span>
            </div>
        <?php } ?>
    
        <script>
            var alert_closes = document.querySelector('.alert-close');
            if (alert_closes.length === undefined) {
                alert_closes = [alert_closes];
            }
            for (var i = 0; i < alert_closes.length; i++) {
                alert_closes[i].addEventListener('click', function (event) {
                    event.target.parentNode.remove();
                });
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
        </style>
    </body>
</html>
