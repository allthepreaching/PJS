<?php

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $code = $_POST["code"];
    $date = $_POST["date"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputUpload($name, $code, $date) !== false) {
        header("location: ../upload.php?error=emptyinput");
        exit();
    }
    uploadVideo($conn, $name, $code, $date);
} else {
    header("location: ../signup.php?error=else");
    exit();
}
