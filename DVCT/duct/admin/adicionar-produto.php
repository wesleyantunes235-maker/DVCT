<?php
require '../config.php';

if(!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$mensagem = '';
$tipo_mensagem = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $quantidade = intval($_POST['quantidade'] ?? 0);
    $codigo = $_POST['codigo'] ?? '';
    $preco_texto = trim($_POST['preco'] ?? '0');
    $preco_texto = preg_replace('/[^0-9,.-]/', '', $preco_texto);
    $preco_texto = str_replace('.', '', $preco_texto);
    $preco = (float) str_replace(',', '.', $preco_texto);
    
    $imagem = '';
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_imagem = uniqid() . '.' . $extensao;
        $caminho = '../imagens/' . $nome_imagem;
        
        if(move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $imagem = $nome_imagem;
        }
    }
    
    $dados['equipamentos'][] = [
        'id' => proximo_id($dados['equipamentos']),
        'nome' => $nome,
        'quantidade' => $quantidade,
        'codigo' => $codigo,
        'preco_diaria' => $preco,
        'imagem' => $imagem,
        'descricao' => 'Equipamento cadastrado pelo admin',
    ];
    salvar_dados($dados);
        $mensagem = 'Equipamento cadastrado com sucesso!';
        $tipo_mensagem = 'sucesso';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Equipamento - DVCT</title>
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
        <div class="form-cadastro">
            <h1>Cadastrar Nova Ferramenta</h1>
            
            <?php if($mensagem): ?>
                <div class="mensagem <?= $tipo_mensagem ?>"><?= $mensagem ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data" class="form-equipamento">
                <div class="upload-imagem">
                    <div class="upload-box" onclick="document.getElementById('imagem').click()">
                        <svg width="60" height="60" viewBox="0 0 60 60">
                            <rect x="10" y="15" width="40" height="30" fill="#ddd" rx="3"/>
                            <circle cx="25" cy="28" r="6" fill="#999"/>
                            <path d="M35 35 L45 25 L50 30 L50 40 L35 40 Z" fill="#999"/>
                            <text x="30" y="55" text-anchor="middle" font-size="20" fill="#666">+</text>
                        </svg>
                        <p>Clique para adicionar imagem</p>
                    </div>
                    <input type="file" id="imagem" name="imagem" accept="image/*" style="display:none" onchange="previewImage(this)">
                    <img id="preview" style="display:none; max-width: 200px; margin-top: 10px;">
                </div>
                
                <div class="form-group">
                    <label>Nome Da Ferramenta:</label>
                    <input type="text" name="nome" required placeholder="Ex: Betoneira CSM">
                    <small>Campo obrigatório</small>
                </div>
                
                <div class="form-group">
                    <label>Quantidade Disponível:</label>
                    <select name="quantidade" required>
                        <?php for($i = 1; $i <= 50; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Codigo Da Ferramenta:</label>
                    <input type="text" name="codigo" required placeholder="Ex: BET001">
                    <small>Código único de identificação</small>
                </div>
                
                <div class="form-group">
                    <label>Preço Aluguel /D:</label>
                    <input type="text" name="preco" required placeholder="Ex: 90,00" onblur="formatarPreco(this)">
                    <small>Preço por dia de locação</small>
                </div>
                
                <button type="submit" class="btn-cadastrar">Cadastrar</button>
            </form>
        </div>
    </div>

    <script>
    function previewImage(input) {
        if(input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('preview').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function formatarPreco(input) {
        let valor = input.value.replace(/\D/g, '');
        valor = (valor / 100).toFixed(2).replace('.', ',');
        input.value = 'R$ ' + valor;
    }
    </script>
</body>
</html>