<?php
session_start();
require_once 'credentials.php';
require_once 'keyauth.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$keyauth = new KeyAuth\api($name, $ownerid, $secret, $version);

// Dados salvos na sessão no momento do login/registro
$username     = $_SESSION['user']['username'];
$subscription = $_SESSION['user']['subscription'] ?? null;
$expiry       = $_SESSION['user']['expiry'] ?? null;

// Converter expiry (timestamp) para data legível
$expiryDate = $expiry ? date("d/m/Y H:i:s", $expiry) : "N/A";

// Calcular tempo restante
$remaining = "";
if ($expiry) {
    $secondsRemaining = $expiry - time();
    if ($secondsRemaining <= 0) {
        $remaining = "Expirada";
    } else {
        $days = floor($secondsRemaining / 86400);
        $hours = floor(($secondsRemaining % 86400) / 3600);
        $minutes = floor(($secondsRemaining % 3600) / 60);
        $remaining = "{$days}d {$hours}h {$minutes}m";
    }
} else {
    $remaining = "N/A";
}

// Exemplo de produtos adquiridos com base na subscription
$produtos = [];
if ($subscription) {
    $produtos[] = [
        'nome' => 'Five Spoofer - ' . htmlspecialchars($subscription, ENT_QUOTES, 'UTF-8'),
        'status' => ($remaining === "Expirada") ? "expirado" : "ativo",
        'tempo' => $remaining
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Five Spoofer</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="dashboard-body">
<div class="bg-animated"></div>

<header class="topbar">
    <div class="topbar-left">
        <span class="logo-small"><span class="logo-accent">FIVE</span> Spoofer</span>
    </div>
    <div class="topbar-right">
        <span class="user-tag">Logado como <strong><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong></span>
        <a href="logout.php" class="btn btn-outline">Sair</a>
    </div>
</header>

<main class="dashboard-main">
    <section class="card highlight-card">
        <h2>Bem-vindo, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?> 👋</h2>
        <p>Obrigado por usar o <strong>Five Spoofer</strong> integrado ao <strong>KeyAuth</strong>.</p>
        <div class="grid">
            <div class="stat">
                <span class="stat-label">Plano</span>
                <span class="stat-value">
                    <?php echo $subscription ? htmlspecialchars($subscription, ENT_QUOTES, 'UTF-8') : "Nenhum"; ?>
                </span>
            </div>
            <div class="stat">
                <span class="stat-label">Expira em</span>
                <span class="stat-value"><?php echo $expiryDate; ?></span>
            </div>
            <div class="stat">
                <span class="stat-label">Tempo restante</span>
                <span class="stat-value"><?php echo $remaining; ?></span>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h3>Produtos Adquiridos</h3>
        </div>
        <?php if (empty($produtos)): ?>
            <p>Você ainda não possui produtos ativos.</p>
        <?php else: ?>
            <div class="products">
                <?php foreach ($produtos as $p): ?>
                    <div class="product-card">
                        <div class="product-title"><?php echo $p['nome']; ?></div>
                        <div class="product-meta">
                            <span class="badge <?php echo $p['status'] === 'ativo' ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo ucfirst($p['status']); ?>
                            </span>
                            <span class="product-time">Tempo restante: <?php echo $p['tempo']; ?></span>
                        </div>
                        <button class="btn btn-primary btn-run" onclick="runSpoofer()">
                            Iniciar Spoofer
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<script src="assets/app.js"></script>
</body>
</html>