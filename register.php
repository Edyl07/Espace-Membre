
<?php

require_once 'inc/functions.php';
session_start();

        if (!empty($_POST)){
            $errors = array();
            require_once 'inc/db.php';
            if (empty($_POST['username'] || !preg_match('/^[a-zA-z0-9_]+$/', $_POST['username']))){
                $errors['username'] = "votre pseudo n'est pas valide (alphanumeric)";
            }else {
                $req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
                $req->execute([$_POST['username']]);
                $user = $req->fetch();
                if ($user){
                    $errors['username'] = "Ce Pseudo existe déja";
                }
            }

            if (empty($_POST['email'] || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))){
                $errors['email'] =  "votre email n'est pas valide";
            }else {
                $req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
                $req->execute([$_POST['email']]);
                $user = $req->fetch();
                if ($user){
                    $errors['email'] = "Cet Email existe déja pour un autre compte";
                }
            }

            if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']){
                $errors['password']  = "votre password n'est pas valide";
            }
            if (empty($errors)){
                $req = $pdo->prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $token = str_random(60);
                $req->execute(array($_POST['username'], $password, $_POST['email'], $token));
                $user_id = $pdo->lastInsertId();
                $_SESSION['flash']['success'] = "Un email de validation à été envoyer pour confirmer votre compte";
                mail($_POST['email'], 'confirmation de votre compte', "afin de valider votre compte cliquez sur ce lien \n\n http://localhost/Comptes/confirm.php?id=$user_id&token=$token");
                header('Location: login.php');
                exit();
            }

        }
    ?>

  <?php require 'inc/header.php'?>

    <h3>S'inscrire</h3>

    <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger">
            <p>Vous n'avez pas remplis le formulaire correctement</p>
           <ul>
               <?php foreach ($errors as $error) : ?>
                <li><?= $error; ?></li>
               <?php endforeach; ?>
           </ul>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-group">
            <label for="">Pseudo</label>
            <input type="text" name="username" class="form-control" id=""  required>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" id=""  required>
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" id="" required>
        </div>
        <div class="form-group">
            <label for="">Confirm Password</label>
            <input type="password" name="password_confirm" id="" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">M'inscrire</button>
    </form>
<?php require 'inc/footer.php' ?>
