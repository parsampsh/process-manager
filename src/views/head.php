<html>
    <head>
        <title>Process Manager</title>

        <style>
            * {
                font-family: Verdana, Geneva, Tahoma, sans-serif;
            }

            body {
                background-color: #ededed;
            }

            .button {
                padding: 10px;
                border: none;
                border-radius: 10px;
                margin: 5px;
                background-color: gray;
            }

            .stop-button {
                background-color: #ff5555;
                color: #fff;
            }
            .stop-button:hover {
                background-color: #ff2222;
            }

            .start-button {
                background-color: green;
                color: #fff;
            }
            .start-button:hover {
                background-color: darkgreen;
            }

            .start-button:disabled, .stop-button:disabled {
                opacity: 0.4;
            }
            .start-button:active, .stop-button:active {
                background-color: black;
            }

            .logs-container {
                background-color: rgb(180, 170, 220);
                padding: 20px;
                margin: 20px;
                border-radius: 10px;
                font-family: Consolas, Monaco, 'Lucida Console', 'Liberation Mono';
            }
        </style>
    </head>

    <body>