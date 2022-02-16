<?php

function __autoload($name) {
    include_once $name . '.php';
}

$db = new DbHandler();
session_start();
if (isset($_POST['login'])) {
    //echo 'eeeeeeeeeeeeeeee';
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    if ($db->login($username, $password)==true) {
        //session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
       // $_SESSION['id'] = $id;
        //echo 'buongiorno'.$_SESSION['loggedin'];
        header('Location: index.php');
    } else {
        echo 'can not log in';
    }
}
