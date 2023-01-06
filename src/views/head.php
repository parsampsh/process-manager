<html>
    <head>
        <title>Process Manager</title>

        <style>
            * {
                font-family: Verdana, Geneva, Tahoma, sans-serif;
            }

            body {
                background-color: #ededed;
                padding 0;
                margin: 0;
            }

            .header {
                background-color: rgb(200, 200, 200);
                padding: 30px;
                margin-bottom: 20px;
            }

            .container {
                padding: 10px 20px;
            }

            .footer {
                background-color: #222;
                color: #ddd;
                padding: 30px;
                text-align: center;
            }

            .footer a {
                text-decoration: none;
                color: rgb(255, 255, 100);
            }

            .button {
                padding: 10px;
                border: none;
                border-radius: 10px;
                margin: 5px;
                background-color: gray;
                transition: all 0.1s;
                text-decoration: none;
            }

            .button:disabled {
                opacity: 0.3;
            }

            .button:active {
                background-color: black !important;
                color: #fff;
            }

            .red-button {
                background-color: #ff4444;
                color: #fff;
            }

            .red-button:hover {
                background-color: #ff0000;
            }

            .green-button {
                background-color: green;
                color: #fff;
            }

            .green-button:hover {
                background-color: darkgreen;
            }

            .blue-button {
                background-color: rgb(100, 120, 220);
                color: #eee;
            }

            .blue-button:hover {
                background-color: blue;
            }

            .logs-container {
                background-color: rgb(220, 220, 220);
                padding: 20px;
                margin: 20px;
                border-radius: 10px;
                font-family: Monospace;
                box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px inset;
                color: #222;
                overflow: auto;
                height: 250px;
            }

            .dropdown {
                padding: 10px;
            }

            .logged-in-as {
                color: #555;
                font-size: 15px;
                position: absolute;
                top: 30;
                right: 30;
            }

            .text-input {
                padding: 10px;
                border-radius: 10px;
                border: solid 1px gray;
                background-color: #eee;
                transition: all 0.2s;
            }

            .text-input:hover {
                background-color: #ccc;
            }

            .text-input:focus {
                background-color: #fff;
                outline: none;
            }

            .alert {
                padding: 50px 10px;
                box-shadow: rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset;
                text-align: center;
                border-radius: 30px;
                margin: 30px 50px;
                position: relative;
            }

            .red-alert {
                background-color: rgb(200, 100, 100);
                color: #fff;
            }

            .alert-close {
                position: absolute;
                top: 30;
                right: 30px;
                cursor: pointer;
                transition: all 0.3s;
            }

            .alert-close:before {
                content: "X";
            }

            .alert-close:hover {
                scale: 1.5;
            }
        </style>
    </head>

    <body>

        <header class="header">
            <h2>Process Manager</h2>
        </header>

        <div class="container">
