<?php
session_start();
require_once 'credentials.php';
require_once 'keyauth.php';

// Inicializar KeyAuth APP (ajuste conforme seu credentials.php)
$keyauth = new KeyAuth\api($name, $ownerid, $secret, $version);

// Se já estiver logado, manda para dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// LOGIN
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === "" || $password === "") {
        $error = "Preencha usuário e senha.";
    } else {
        $keyauth->login($username, $password);

        if ($keyauth->response->success) {
            $_SESSION['user'] = [
                'username' => $username,
                'subscription' => $keyauth->response->info->subscriptions[0]->subscription ?? null,
                'expiry' => $keyauth->response->info->subscriptions[0]->expiry ?? null,
            ];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $keyauth->response->message;
        }
    }
}

// REGISTRO (username + password + key)
if (isset($_POST['register'])) {
    $username = trim($_POST['reg_username'] ?? '');
    $password = trim($_POST['reg_password'] ?? '');
    $license  = trim($_POST['reg_license'] ?? '');

    if ($username === "" || $password === "" || $license === "") {
        $error = "Preencha todos os campos de registro.";
    } else {
        $keyauth->register($username, $password, $license);

        if ($keyauth->response->success) {
            $_SESSION['user'] = [
                'username' => $username,
                'subscription' => $keyauth->response->info->subscriptions[0]->subscription ?? null,
                'expiry' => $keyauth->response->info->subscriptions[0]->expiry ?? null,
            ];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = $keyauth->response->message;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Five Spoofer | Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="bg-animated"></div>

<div class="auth-container">
    <div class="logo">
        <span class="logo-accent">FIVE</span> Spoofer
    </div>
    <p class="subtitle">Autenticação segura com KeyAuth</p>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <div class="tabs">
        <button class="tab-btn active" data-tab="login">Login</button>
        <button class="tab-btn" data-tab="register">Registrar</button>
    </div>

    <!-- LOGIN -->
    <form method="POST" class="form active" id="form-login">
        <div class="input-group">
            <label>Usuário</label>
            <input type="text" name="username" placeholder="Seu usuário">
        </div>
        <div class="input-group">
            <label>Senha</label>
            <input type="password" name="password" placeholder="Sua senha">
        </div>
        <button type="submit" name="login" class="btn btn-primary">
            Entrar
        </button>
    </form>

    <!-- REGISTRO -->
    <form method="POST" class="form" id="form-register">
        <div class="input-group">
            <label>Usuário</label>
            <input type="text" name="reg_username" placeholder="Crie um usuário">
        </div>
        <div class="input-group">
            <label>Senha</label>
            <input type="password" name="reg_password" placeholder="Crie uma senha">
        </div>
        <div class="input-group">
            <label>Key (Licença)</label>
            <input type="text" name="reg_license" placeholder="Cole sua key KeyAuth">
        </div>
        <button type="submit" name="register" class="btn btn-secondary">
            Registrar
        </button>
    </form>

    <footer class="footer">
        Five Spoofer • Integrado com KeyAuth
    </footer>
</div>

<script src="assets/app.js"></script>
</body>
</html>