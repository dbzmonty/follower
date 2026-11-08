<?php
    session_start();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    header("location:login.php?succlogout=true");
?>
