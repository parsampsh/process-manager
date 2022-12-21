# Process Manager
This small PHP dashboard is created to solve this problem:

> We have a script that runs in the background continuously.
> It also logs some stuff in a file.
> We need a web panel to be able to start and stop the process.
> And see it's logs.
> Simple as that.

## Setup
To set this thing up, first clone the project, then:

Copy `settings.example.php` to `settings.php` and edit the [settings](#Configuration) in it.

Then:

```shell
$ php -S localhost:8000
```

And go to http://localhost:8000/dashboard.php and use the app!

## Configuration
Totally we have 2 elements in the configuration file (`settings.example.php`): **Users** and **Commands**

### Users
You can implement different users in the config file.
Each user has **username** and **password** and they can login to the dashboard using them.

Also you can change permissions of each user and restrict their access to different actions.

And also all of the actions of the users will be logged into a file.

```php
// You can set the users here. Put the username in array key, in in each you can set password and permissions
// there are 4 permissions: PERMISSION_READ_LOG, PERMISSION_READ_STATS, PERMISSION_START, PERMISSION_STOP
// You can set permissions of the user to each command like this:
// You can put them in an array like this:
// 'permissions_for_commands' => [
//    'Main' => [PERMISSION_READ_LOG, PERMISSION_READ_STATS],
// ],
// Also if you want to give someone all of the permissions, you don't need to write them all.
// You can write it this way: 'Main' => PERMISSION_ALL
const USERS = [
    'admin' => [
        'password' => 'admin',
        'permissions_for_commands' => [
            'Main' => PERMISSION_ALL,
            'Second' => [
                PERMISSION_READ_LOG,
            ],
        ],
    ],
];
```

As you can see above, there are some signs of **Commands**. We'll learn about them in the next section.

Also there is another variable to set the log file for user actions:

```php
// The user logs will be saved into this file
// The logs include datetime, username and the action happened
// For example it says user Admin has started the process at datetime X
const USER_LOGS_FILE = 'user-logs.txt';
```

An example of `user-logs.txt` content:

```
[2022-12-21 21:47:41] [User admin] Stopped the process
[2022-12-21 21:47:41] [User admin] Started the process
[2022-12-21 21:47:41] [User admin] Read the process logs
...
```

There is also another scenario that **you don't want to implement any user and want anyone to be able to open the dashboard**.
In that case, just leave the `USERS` array empty. Simple as that.
Then everyone can open the dashboard without having to enter any username or password.
And they would probably have full permission on everything.

### Commands
You can implement multiple commands (scripts, processes or whatever you call them) in the system.
They will appear in a dropdown in the main dashboard.
User can select them and start/stop them or see their logs, etc.

```php
// You can implement multiple commands here
// User can select which command to manage in a dropdown in the main page of the app
const COMMANDS = [
    'Main' => [
        'command' => 'python3 test-script.py', // the command to run
        'working_dir' => __DIR__, // working directory of the command
        'log_file' => __DIR__ . '/log-file.txt', // a file to log command output to it
        'log_tail_maximum_lines' => 20, // number of the lines for the log file tail when we show the logs
        'process_id_file' => 'process-id.txt', // a file to store the process id for the command
        'kill_signal' => 'TERM', // the signal you want to be sent to the command when the stop button gets pressed
    ],
    'Second' => [
        'command' => 'python3 test-script.py',
        'working_dir' => __DIR__,
        'log_file' => __DIR__ . '/log-file-2.txt',
        'log_tail_maximum_lines' => 20,
        'process_id_file' => 'process-id-2.txt',
        'kill_signal' => 'TERM',
    ],
];
```

As you saw in the [Users](#Users) section, we can give access of different commands to different users, with different permissions.

```php
const USERS = [
    'admin' => [
        'password' => 'admin',
        'permissions_for_commands' => [
            'Main' => PERMISSION_ALL,
            'Second' => [
                PERMISSION_READ_LOG,
            ],
        ],
    ],
];
```

In the `permissions_for_commands`, we use names of the commands in array keys,
and permissions for them in the array value.

## API
This app also has an API system.
There are 5 actions available:

- `status`: Get status of the process
- `logs`: Get logs of the process
- `stats`: Get stats of the process
- `start`: Start the process
- `stop`: Stop the process

```shell
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main
```

As you can see above, `username`, `password` and `command` arguments should always be passed to the APIs.

If there is no user configured, you can ignore `username` and `password`.
You can also ignore the `command` too, if you do the first command will be selected by default.

Then you can use `action` argument to do what you want:

```shell
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main&action=status
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main&action=logs
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main&action=stats
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main&action=start
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main&action=stop
```

The APIs check the permissions, and if you don't have a permission to do something,
they will return `403` response code. And a JSON like this:

```json
{
    "ok": false,
    "message": "You don't have permission to ..."
}
```

The `ok` field is in all of the responses.
If it's `true` it means there is no error.
But if it's `false` the error message will be written in `message`.

There is also another `action` that you can get list of the commands with it:

```shell
$ curl http://localhost:8000/api.php?username=admin&password=123&command=Main&action=commands_list
```

## License
This project is created and maintained by [Parsa](https://github.com/parsampsh) and licensed under [MIT License](LICENSE).
