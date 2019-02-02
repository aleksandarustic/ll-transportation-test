<?php

define('CLIENT_ID','1052048757108-3ha9prsgrrui9rj5gu3rdmi46mqhjruh.apps.googleusercontent.com');
define('CLIENT_SECRET', 'f6TTpf9jg6ZFVSVbOF1uPvx9');
define('CALENDAR_ID','aleksandar.ustic.gcalendar@gmail.com');

define('TIMEZONE','Europe/Belgrade');
define('REDIRECT_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . '/ll-transportation-test/callback.php');

?>