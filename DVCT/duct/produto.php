<?php
require 'config.php';

$id = $_GET['id'] ?? 0;
$equipamento = null;
foreach($dados['equipamentos'] as $item) {
    if((int) $item['id'] === (int) $id) {
        $equipamento = $item;
        break;
    }
}

if(!$equipamento) {
    header('Location: index.php');
    exit;
}

$imagem = $equipamento['imagem'] ?? '';
$caminho_imagem = __DIR__ . '/imagens/' . basename($imagem);
if ($imagem && !is_file($caminho_imagem)) {
    $imagem_svg = pathinfo($imagem, PATHINFO_FILENAME) . '.svg';
    if (is_file(__DIR__ . '/imagens/' . $imagem_svg)) {
        $imagem = $imagem_svg;
    }
}
if (!$imagem || !is_file(__DIR__ . '/imagens/' . basename($imagem))) {
    $imagem = 'martelo.svg';
}

$mensagem = '';
$quantidade_disponivel = max(1, (int) ($equipamento['quantidade'] ?? 1));

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dias = max(1, intval($_POST['dias'] ?? 1));
    $quantidade = max(1, intval($_POST['quantidade'] ?? 1));

    if($quantidade > $quantidade_disponivel) {
        $mensagem = '<div class="erro">A quantidade solicitada é maior que a disponível.</div>';
    } else {
        $valor_total = $dias * $quantidade * $equipamento['preco_diaria'];

        $dados['solicitacoes'][] = [
            'id' => proximo_id($dados['solicitacoes']),
            'usuario_id' => (int) $_SESSION['usuario_id'],
            'equipamento_id' => (int) $id,
            'dias' => $dias,
            'quantidade' => $quantidade,
            'valor_total' => $valor_total,
            'status' => 'solicitado',
            'data_solicitacao' => date('Y-m-d H:i:s'),
            'data_inicio' => null,
            'data_fim' => null,
        ];
        salvar_dados($dados);

        $mensagem = '<div class="sucesso">Solicitação enviada com sucesso!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $equipamento['nome'] ?> - DVCT</title>
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

    <div class="container">
        <?= $mensagem ?>
        
        <div class="produto-detalhe">
            <div class="produto-imagem">
                <img src="imagens/<?= htmlspecialchars($imagem, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($equipamento['nome'], ENT_QUOTES, 'UTF-8') ?>">
            </div>
            
            <div class="produto-info">
                <h1><?= $equipamento['nome'] ?></h1>
                <p class="preco-grande">R$<?= number_format($equipamento['preco_diaria'], 2, ',', '.') ?>/D</p>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Unidade de retirada:</label>
                        <select name="unidade">
                            <option>Matriz</option>
                            <option>Filial 1</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="dias">Quantidade de dias:</label>
                        <input type="number" id="dias" name="dias" min="1" value="1" required>
                    </div>

                    <div class="form-group">
                        <label for="quantidade">Quantidade de unidades:</label>
                        <input type="number" id="quantidade" name="quantidade" min="1" max="<?= $quantidade_disponivel ?>" value="1" required>
                        <small><?= $quantidade_disponivel ?> unidade(s) disponível(is)</small>
                    </div>

                    <p class="valor-estimado">Valor estimado: <strong id="valor-total">R$<?= number_format($equipamento['preco_diaria'], 2, ',', '.') ?></strong></p>
                    
                    <button type="submit" class="btn-solicitar">SOLICITAR</button>
                </form>
            </div>
        </div>
        
        <div class="descricao">
            <h3>Descrição</h3>
            <p><?= $equipamento['descricao'] ?></p>
        </div>
    </div>

    <script>
    const diasInput = document.getElementById('dias');
    const quantidadeInput = document.getElementById('quantidade');
    const valorTotal = document.getElementById('valor-total');
    const precoDiaria = <?= json_encode((float) $equipamento['preco_diaria']) ?>;

    function atualizarValorTotal() {
        const dias = Math.max(1, parseInt(diasInput.value, 10) || 1);
        const quantidade = Math.max(1, parseInt(quantidadeInput.value, 10) || 1);
        valorTotal.textContent = (dias * quantidade * precoDiaria).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
    }

    diasInput.addEventListener('input', atualizarValorTotal);
    quantidadeInput.addEventListener('input', atualizarValorTotal);
    </script>
</body>
</html>