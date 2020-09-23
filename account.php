<?php
    session_start();
    require 'inc/functions.php';
    logged_only();
?>

<?php require 'inc/header.php' ?>

    <h1>Votre Compte</h1>
    <?php debug($_SESSION); ?>

<?php require 'inc/footer.php' ?>
