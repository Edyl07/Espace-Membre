<?php
    if(isset($_GET['id']) && isset($_GET['token'])){
        require 'inc/db.php';
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ? AND reset_token = IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTES)');
        $req->execute([$_GET['id'], $_GET['token']]);
        $user = $req->fetch();
        if ($user){
            if (!empty($_POST)){
                if(!empty($_POST['password']) && $_POST['password'] =! $_POST['confirm_password']){
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $pdo->prepare('UPDATE users SET password = ? AND reset_token = NULL AND reset_at = NULL')->execute([$password]);
                    session_start();
                    $_SESSION['flash']['success'] = "Votre mot de passe a été réinitialiser avec succès";
                    $_SESSION['auth'] = $user;
                    header('Location: account.php');
                }
            }
        }else{
            session_start();
            $_SESSION['flash']['danger'] = "Cette Session n'est pas valide";
            header('Location: login.php');
            exit();
        }
    }else{
        header('Location: login.php');
        exit();
    }
?>

<?php require 'inc/header.php' ?>

<h1>Se Connecter</h1>
<form action="" method="post">

    <div class="form-group">
        <label for="">Mot de passe </label>
        <input type="password" name="password" class="form-control" id="" required>
    </div>
    <div class="form-group">
        <label for="">Confirmation de Mot de passe </label>
        <input type="password" name="confirm_password" class="form-control" id="" required>
    </div>
    <button type="submit" class="btn btn-primary">Réeinitailiser mon mot de passe</button>
</form>

<?php require 'inc/footer.php' ?>
