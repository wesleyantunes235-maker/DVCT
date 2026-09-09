<?php
require 'config.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar'])) {
    $id = intval($_POST['cancelar']);
    foreach($dados['solicitacoes'] as &$solicitacao) {
        if((int) $solicitacao['id'] === $id
            && (int) $solicitacao['usuario_id'] === (int) $_SESSION['usuario_id']
            && !in_array($solicitacao['status'], ['cancelado', 'devolvido'], true)) {
            $solicitacao['status'] = 'cancelado';
        }
    }
    unset($solicitacao);
    salvar_dados($dados);
    header('Location: minhas-solicitacoes.php');
    exit;
}

$solicitacoes = [];
foreach($dados['solicitacoes'] as $solicitacao) {
    if((int) $solicitacao['usuario_id'] !== (int) $_SESSION['usuario_id']) {
        continue;
    }
    foreach($dados['equipamentos'] as $equipamento) {
        if((int) $equipamento['id'] === (int) $solicitacao['equipamento_id']) {
            $solicitacoes[] = array_merge($solicitacao, [
                'equipamento_nome' => $equipamento['nome'],
                'preco_diaria' => $equipamento['preco_diaria'],
                'imagem' => $equipamento['imagem'],
            ]);
            break;
        }
    }
}
usort($solicitacoes, fn($a, $b) => strcmp($b['data_solicitacao'], $a['data_solicitacao']));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minhas Solicitações - DVCT</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">DVCT</div>
            <nav>
                <a href="minhas-solicitacoes.php">Contato</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <?php if(empty($solicitacoes)): ?>
            <div class="vazio">
                <div class="ilustracao-vazio">
                    <svg width="200" height="200" viewBox="0 0 200 200">
                        <path d="M60 120 L100 140 L140 120 L140 170 L100 190 L60 170 Z" fill="#e8f0fe" stroke="#4285f4" stroke-width="2"/>
                        <path d="M60 120 L100 100 L140 120" fill="none" stroke="#4285f4" stroke-width="2"/>
                        <path d="M100 100 L100 140" fill="none" stroke="#4285f4" stroke-width="2" stroke-dasharray="4"/>
                        <path d="M50 110 L100 90 L150 110 L140 120 L100 100 L60 120 Z" fill="#fff" stroke="#4285f4" stroke-width="2"/>
                        <path d="M130 80 A20 20 0 1 1 110 80" fill="none" stroke="#4285f4" stroke-width="2"/>
                        <polygon points="110,75 110,85 105,80" fill="#4285f4"/>
                        <path d="M70 125 L100 135 L130 125 L130 140 L100 150 L70 140 Z" fill="#4285f4"/>
                    </svg>
                </div>
                <h2>Você Ainda Não Alugou Nada</h2>
                <a href="index.php" class="btn">Ver Equipamentos</a>
            </div>
        <?php else: ?>
            <h1>Minhas Solicitações</h1>
            <div class="lista-solicitacoes">
                <?php foreach($solicitacoes as $s): ?>
                <div class="item-solicitacao">
                    <div class="info-equipamento">
                        <img src="imagens/<?= $s['imagem'] ?>" alt="<?= $s['equipamento_nome'] ?>">
                        <div>
                            <h3><?= ucfirst($s['status']) ?></h3>
                            <p><?= $s['equipamento_nome'] ?></p>
                        </div>
                    </div>
                    
                    <div class="info-valores">
                        <p><strong>Quantidade de dias:</strong> <?= (int) $s['dias'] ?></p>
                        <p><strong>Valor total:</strong> R$<?= number_format($s['valor_total'], 2, ',', '.') ?></p>
                        <?php if(!in_array($s['status'], ['cancelado', 'devolvido'], true)): ?>
                            <form method="POST" onsubmit="return confirm('Deseja cancelar esta solicitação?')">
                                <input type="hidden" name="cancelar" value="<?= (int) $s['id'] ?>">
                                <button type="submit" class="btn-cancelar">Cancelar solicitação</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>