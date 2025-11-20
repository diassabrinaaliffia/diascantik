<?php
// helpers.php
require_once 'koneksi.php';

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function flash($name = '', $message = '')
{
    if ($name && $message) {
        $_SESSION['flash'][$name] = $message;
    } elseif ($name && isset($_SESSION['flash'][$name])) {
        $msg = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $msg;
    }
    return null;
}