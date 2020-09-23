<?php require 'inc/functions.php' ?>

<?php
    if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
        require 'inc/db.php';
        $req = $pdo->prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
        $req->execute(['username' => $_POST['username']]);
        $user = $req->fetch();
        if (password_verify($_POST['password'], $user->password)){
            session_start();
            $_SESSION['auth'] = $user;
            $_SESSION['flash']['success'] = "Vous êtes maintenant connecté au site";
            header('Location: account.php');
            exit();
        }else{
            $_SESSION['flash']['danger'] = "Identifiant ou mot de passe incorrect";
        }
    }
?>

<?php require 'inc/header.php' ?>

        <h1>Se Connecter</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Pseudo ou Email</label>
                <input type="text" name="username" class="form-control" id=""  required>
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input type="password" name="password" class="form-control" id="" required>
            </div>
            <button type="submit" class="btn btn-primary">M'inscrire</button>
        </form>

<?php require 'inc/footer.php' ?>
