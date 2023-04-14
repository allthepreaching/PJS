<?php

function emptyInputSignup($email, $pwd, $pwd2)
{
    $result;
    if (empty($email) || empty($pwd) || empty($pwd2)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function InvalidEmail($email)
{
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emptyPwdMatch($pwd, $pwd2)
{
    $result;
    if ($pwd !== $pwd2) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function emailExists($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}

function createUser($conn, $email, $pwd)
{
    $sql = "INSERT INTO users (email, pwd) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../login.php?error=none");
    exit();
}

function emptyInputLogin($email, $pwd)
{
    $result;
    if (empty($email) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $email, $pwd)
{
    $emailExists = emailExists($conn, $email);

    if ($emailExists === false) {
        header("location: ../login.php?error=emailerror");
        exit();
    }

    $pwdHashed = $emailExists['pwd'];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../login.php?error=pwderror");
        exit();
    } elseif ($checkPwd === true) {
        session_start();
        $_SESSION["id"] = $emailExists['id'];
        $_SESSION["email"] = $emailExists['email'];
        header("location: ../upload.php");
        exit();
    }
}

function emptyInputUpload($name, $code, $date)
{
    $result;
    if (empty($name) || empty($code) || empty($date)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function uploadVideo($conn, $name, $code, $date)
{
    $sql = "INSERT INTO pjsUpload (vid_title, vid_code, date) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../upload.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $name, $code, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../upload.php?error=none");
    exit();
}
