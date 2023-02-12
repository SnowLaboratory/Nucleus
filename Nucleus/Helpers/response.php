<?php

function back() {
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header("location:javascript://history.go(-1)");
    }
}

function abort_raw($code, $message)
{
    header("HTTP/1.0 $code OK");
    die($message);
}