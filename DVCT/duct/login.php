<?php
require 'config.php';

$erro = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    $usuario = null;
    foreach($dados['usuarios'] as $item) {
        $senhaSalva = $item['senha'] ?? '';
        $senhaValida = $senhaSalva === md5($senha)
            || $senhaSalva === $senha
            || $senhaSalva === md5(trim($senha));

        if(($item['email'] ?? '') === $email && $senhaValida) {
            $usuario = $item;
            break;
        }
    }
    
    if($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_tipo'] = $usuario['tipo'];
        header('Location: ' . ($usuario['tipo'] === 'admin' ? 'admin/dashboard.php' : 'index.php'));
        exit;
    } else {
        $erro = 'Email ou senha incorretos!';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - DVCT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="pagina-login">
    <header class="header-login">
        <div class="container-login">
            <div class="logo-login">DVCT</div>
            <nav class="nav-login">
                <a href="#">Contato</a>
            </nav>
        </div>
    </header>

    <div class="login-container">
        <h1>DVCT Construções</h1>

        <div class="botoes-social">
            <button class="btn-google">
                <img src="imagens/google-icon.svg" alt="Google">
                <span>Google</span>
            </button>
            <button class="btn-facebook">
                <img src="imagens/facebook-icon.svg" alt="Facebook">
                <span>Facebook</span>
            </button>
        </div>
        
        <div class="divisor">
            <span>ou</span>
        </div>
        
        <?php if($erro): ?>
            <div class="erro"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST" class="form-login">
            <div class="form-group-login">
                <label>Login</label>
                <input type="email" name="email" placeholder="ex: nome@gmail.com" required>
            </div>
            
            <div class="form-group-login">
                <label>Senha</label>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>