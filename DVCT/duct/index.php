<?php
require 'config.php';

$equipamentos = $dados['equipamentos'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>DVCT - Locação de Equipamentos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">DVCT</div>
            <nav>
                <a href="index.php">Início</a>
                <a href="minhas-solicitacoes.php">Minhas Solicitações</a>
            </nav>
        </div>
    </header>

    <div class="banner">
        <img src="imagens/ferramentas.svg" alt="Ferramentas">
    </div>

    <div class="container">
        <h1>Catálogo de Equipamentos</h1>
        
        <div class="grid-equipamentos">
            <?php foreach($equipamentos as $equip): ?>
            <div class="card">
                <?php
                $imagem = $equip['imagem'] ?? '';
                $caminho_imagem = __DIR__ . '/imagens/' . basename($imagem);
                if ($imagem && !is_file($caminho_imagem)) {
                    $imagem_svg = pathinfo($imagem, PATHINFO_FILENAME) . '.svg';
                    $caminho_svg = __DIR__ . '/imagens/' . $imagem_svg;
                    if (is_file($caminho_svg)) {
                        $imagem = $imagem_svg;
                    }
                }
                if (!$imagem || !is_file(__DIR__ . '/imagens/' . basename($imagem))) {
                    $imagem = 'martelo.svg';
                }
                ?>
                <img src="imagens/<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($equip['nome'], ENT_QUOTES, 'UTF-8') ?>">
                <h3><?= strtoupper($equip['nome']) ?></h3>
                <p class="preco">R$<?= number_format($equip['preco_diaria'], 2, ',', '.') ?>/D</p>
                <a href="produto.php?id=<?= $equip['id'] ?>" class="btn">Ver Detalhes</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>