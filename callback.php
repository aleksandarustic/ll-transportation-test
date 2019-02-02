<?php

require_once('./classes/App.php');

if(isset($_GET['code'])){

    $authCode = $_GET['code'];
    App::create_new_token($authCode);
    header('Location:index.php');

}
