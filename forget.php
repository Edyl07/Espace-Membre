<?php require 'inc/functions.php' ?>

<?php
if (!empty($_POST) && !empty($_POST['email'])){
    require 'inc/db.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if ($user){
        session_start();
        $reset_token = str_random(60);
        $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?')->execute([$reset_token, $user->id]);
        $_SESSION['flash']['success'] = "Veuillez vous connecter à votre email pour recupérer votre Mot de Passe";
        $headers = 'From: webmaster@example.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion();
        mail($_POST['email'], 'Réinitialisation de votre mot de passe',
            "afin de réinitialiser votre mot de passe cliquez sur ce lien \n\n http://localhost/Comptes/reset.php?id={$user->id}d&token={$reset_token}", $headers);
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['flash']['danger'] = "Aucun compte ne correspond à cette Email";
    }
}
?>

<?php require 'inc/header.php' ?>

<h1>Mot de passe Oublié</h1>
<form action="" method="post">
    <div class="form-group">
        <label for="">Email</label>
        <input type="email" name="email" class="form-control" id=""  required>
    </div>
    <button type="submit" class="btn btn-primary">M'inscrire</button>
</form>

<?php require 'inc/footer.php' ?>
