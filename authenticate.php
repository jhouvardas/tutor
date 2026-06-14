<?php

function __autoload($name)
{
    include_once $name . '.php';
}

$db = new DbHandler();
session_start();
if (isset($_POST['login'])) {
    //echo 'eeeeeeeeeeeeeeee';
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    if ($db->login($username, $password) == true) {
        //session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];

        // Προεπιλεγμένο σχολικό έτος: Αν υπάρχει το Cookie το χρησιμοποιούμε, αλλιώς το υπολογίζουμε (μετά τον Αύγουστο)
        if (isset($_COOKIE['preferred_school_year'])) {
            $_SESSION['active_school_year'] = (int)$_COOKIE['preferred_school_year'];
        } else {
            $_SESSION['active_school_year'] = (date('n') >= 8) ? (int)date('Y') + 1 : (int)date('Y');
        }
        // $_SESSION['id'] = $id;
        //echo 'buongiorno'.$_SESSION['loggedin'];
        header('Location: index.php');
    } else {
        echo 'can not log in';
    }
}
