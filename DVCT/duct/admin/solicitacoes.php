<?php
require '../config.php';

if(!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if(isset($_GET['acao']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $acao = $_GET['acao'];

    foreach($dados['solicitacoes'] as &$solicitacao) {
        if((int) $solicitacao['id'] !== $id) {
            continue;
        }
        if($acao === 'aprovar') {
            $solicitacao['status'] = 'ativo';
            $solicitacao['data_inicio'] = date('Y-m-d H:i:s');
            $solicitacao['data_fim'] = date('Y-m-d H:i:s', strtotime('+' . $solicitacao['dias'] . ' days'));
        } elseif($acao === 'negar') {
            $solicitacao['status'] = 'cancelado';
        }
    }
    unset($solicitacao);
    salvar_dados($dados);
    
    header('Location: solicitacoes.php');
    exit;
}

$solicitacoes = [];
foreach($dados['solicitacoes'] as $solicitacao) {
    if($solicitacao['status'] !== 'solicitado') {
        continue;
    }
    $equipamento = current(array_filter($dados['equipamentos'], fn($item) => $item['id'] === $solicitacao['equipamento_id']));
    $usuario = current(array_filter($dados['usuarios'], fn($item) => $item['id'] === $solicitacao['usuario_id']));
    if($equipamento && $usuario) {
        $solicitacoes[] = array_merge($solicitacao, [
            'equipamento_nome' => $equipamento['nome'],
            'imagem' => $equipamento['imagem'],
            'preco_diaria' => $equipamento['preco_diaria'],
            'cliente_nome' => $usuario['nome'],
            'cliente_empresa' => $usuario['empresa'],
        ]);
    }
}
usort($solicitacoes, fn($a, $b) => strcmp($b['data_solicitacao'], $a['data_solicitacao']));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitações - DVCT</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="admin-container">
            <div class="logo">DVCT</div>
            <nav>
                <a href="dashboard.php">Voltar</a>
            </nav>
        </div>
    </header>

    <div class="admin-container">
        <h1>Solicitações de Aluguel</h1>
        
        <?php if(empty($solicitacoes)): ?>
            <div class="sem-solicitacoes">
                <p>Nenhuma solicitação pendente</p>
            </div>
        <?php else: ?>
            <div class="lista-solicitacoes-admin">
                <?php foreach($solicitacoes as $s): ?>
                <div class="card-solicitacao">
                    <div class="card-header" onclick="toggleDetails(<?= $s['id'] ?>)">
                        <div class="equipamento-info">
                            <img src="../imagens/<?= $s['imagem'] ?>" alt="<?= $s['equipamento_nome'] ?>">
                            <h3>Pedido De Aluguel</h3>
                        </div>
                        <span class="toggle-icon">▼</span>
                    </div>
                    
                    <div class="card-body" id="details-<?= $s['id'] ?>">
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Quantidade:</strong> <?= $s['quantidade'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Solicitante:</strong> <?= $s['cliente_empresa'] ?: $s['cliente_nome'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Dias Requeridos:</strong> <?= $s['dias'] ?>
                            </div>
                            <div class="info-item">
                                <strong>Codigo Do Pedido:</strong> <?= $s['id'] ?>
                            </div>
                            <div class="info-item full-width">
                                <strong>Preco Total Cobrado:</strong> R$ <?= number_format($s['valor_total'], 2, ',', '.') ?>
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <a href="?acao=aprovar&id=<?= $s['id'] ?>" 
                               class="btn-confirmar"
                               onclick="return confirm('Confirmar aprovação deste aluguel?')">
                                Confirmar
                            </a>
                            <a href="?acao=negar&id=<?= $s['id'] ?>" 
                               class="btn-negar"
                               onclick="return confirm('Deseja negar esta solicitação?')">
                                Negar
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function toggleDetails(id) {
        const details = document.getElementById('details-' + id);
        details.style.display = details.style.display === 'none' ? 'block' : 'none';
    }
    </script>
</body>
</html>