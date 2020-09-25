<?php
    session_start();
    setcookie('remember', null, -1);
    unset($_SESSION['auth']);
    $_SESSION['flash']['success'] = "Vous êtes maintenant déconnecté";
    header('Location: login.php');
