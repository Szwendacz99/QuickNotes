<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="public/css/main.css">
    <link rel="stylesheet" type="text/css" href="public/css/login.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickNotes login page</title>
</head>
<body>
    <div class="logo">
        <h1>QuickNotes</h1>
    </div>
    <form class="login-form" method="POST">
        <div class="login-info">
            <?php
            if (isset($message)){
                echo $message;
            }
            ?>
        </div>
        <input class="login-input" type="text" placeholder="username" name="username">
        <input class="login-input" type="text" placeholder="email" name="email">
        <input class="login-input" type="password" placeholder="password" name="password">
        <input class="login-input" type="password" placeholder="confirm password" name="password_confirm">
        <button class="login-button" type="submit">REGISTER</button>
        <a href="/login" class="login-bottom-text">I have an account</a>
    </form>
    
</body>
</html>