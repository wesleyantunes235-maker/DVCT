<?php
require '../config.php';

if(!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$total_equipamentos = count($dados['equipamentos']);
$solicitacoes_pendentes = count(array_filter($dados['solicitacoes'], fn($item) => $item['status'] === 'solicitado'));
$alugueis_ativos = count(array_filter($dados['solicitacoes'], fn($item) => $item['status'] === 'ativo'));
$alugueis = [];
foreach($dados['solicitacoes'] as $solicitacao) {
    if($solicitacao['status'] !== 'ativo') {
        continue;
    }
    $equipamento = current(array_filter($dados['equipamentos'], fn($item) => $item['id'] === $solicitacao['equipamento_id']));
    $usuario = current(array_filter($dados['usuarios'], fn($item) => $item['id'] === $solicitacao['usuario_id']));
    if($equipamento && $usuario) {
        $alugueis[] = array_merge($solicitacao, [
            'equipamento_nome' => $equipamento['nome'],
            'codigo' => $equipamento['codigo'],
            'cliente_nome' => $usuario['nome'],
            'cliente_empresa' => $usuario['empresa'],
        ]);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - DVCT</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="admin-container">
            <div class="logo">DVCT</div>
            <nav>
                <a href="dashboard.php">Início</a>
                <a href="logout.php">Sair</a>
            </nav>
        </div>
    </header>

    <div class="admin-container">
        <div class="welcome-section">
            <div class="user-avatar">
                <svg width="80" height="80" viewBox="0 0 80 80">
                    <circle cx="40" cy="40" r="35" fill="#ddd"/>
                    <circle cx="40" cy="30" r="12" fill="#666"/>
                    <path d="M20 65 Q40 45 60 65" fill="#666"/>
                </svg>
            </div>
            <h1>Bem-Vindo *USER ADM*</h1>
        </div>

        <div class="admin-menu">
            <a href="solicitacoes.php" class="menu-item">
                <span>Solicitacoes</span>
                <?php if($solicitacoes_pendentes > 0): ?>
                    <span class="badge"><?= $solicitacoes_pendentes ?></span>
                <?php endif; ?>
                <span class="arrow">▶</span>
            </a>
            <a href="adicionar-produto.php" class="menu-item">
                <span>Adicionar Produto</span>
                <span class="plus">+</span>
            </a>
        </div>

        <div class="alugueis-ativos">
            <h2>Alugueis Ativos</h2>
            
            <?php if(empty($alugueis)): ?>
                <p class="sem-registros">Nenhum aluguel ativo no momento</p>
            <?php else: ?>
                <div class="tabela-alugueis">
                    <table>
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Período</th>
                                <th>Equipamento</th>
                                <th>Código</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($alugueis as $aluguel): ?>
                            <tr>
                                <td><?= $aluguel['cliente_empresa'] ?: $aluguel['cliente_nome'] ?></td>
                                <td>
                                    <?= date('d/m/y', strtotime($aluguel['data_inicio'])) ?>-
                                    <?= date('d/m/y', strtotime($aluguel['data_fim'])) ?>
                                    <span class="dropdown">▼</span>
                                </td>
                                <td><?= $aluguel['equipamento_nome'] ?></td>
                                <td>Codigo Da Ferramenta: <?= $aluguel['codigo'] ?></td>
                                <td class="acoes">
                                    <button class="btn-atraso" onclick="contestarAtraso(<?= $aluguel['id'] ?>)">
                                        Contestar Atraso
                                    </button>
                                    <button class="btn-avaria" onclick="registrarAvaria(<?= $aluguel['id'] ?>)">
                                        Registrar Avaria
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function contestarAtraso(id) {
        if(confirm('Deseja contestar o atraso deste aluguel?')) {
            alert('Atraso contestado com sucesso!');
        }
    }

    function registrarAvaria(id) {
        if(confirm('Deseja registrar avaria neste equipamento?')) {
            alert('Avaria registrada com sucesso!');
        }
    }
    </script>
</body>
</html>