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
        <input class="login-input" type="text" placeholder="email" name="email">
        <input class="login-input" type="password" placeholder="password" name="password">
        <button class="login-button" type="submit">LOGIN</button>
        <a href="" class="login-bottom-text">Create new account</a>
    </form>
    
</body>
</html>