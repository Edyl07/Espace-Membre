<?php
    session_start();
    require 'inc/functions.php';
    require 'inc/header.php';
    logged_only();

    if(!empty($_POST)){
        if (!empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
            $_SESSION['flash']['danger'] = "les mot de passe ne correspondent pas";
        }else{
            $user_id = $_SESSION['auth']->id;
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            require 'inc/db.php';
            $pdo->prepare('UPDATE users SET password = ?')->execute([$password]);
            $_SESSION['flash']['success'] = "Votre mot de passe à bien été mis à jour";
        }
    }

?>


    <h1>Votre Compte <?= $_SESSION['auth']->username; ?> </h1>

    <form action="" method="post">
        <div class="form-group">
            <label for="">Mot de passe</label>
            <input type="password" name="password" id="" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Confirmer le Mot de passe</label>
            <input type="password" name="password_confirm" id="" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Changer de mot de passe</button>
    </form>

<?php require 'inc/footer.php' ?>
