<?php
session_start();

$arquivo_dados = __DIR__ . '/dados.php';

if (!is_file($arquivo_dados)) {
    die('Arquivo de dados não encontrado.');
}

$dados = require $arquivo_dados;

function salvar_dados(array $dados): void
{
    $conteudo = "<?php\n\nreturn " . var_export($dados, true) . ";\n";
    file_put_contents(__DIR__ . '/dados.php', $conteudo, LOCK_EX);
}

function proximo_id(array $itens): int
{
    return empty($itens) ? 1 : max(array_column($itens, 'id')) + 1;
}

if(!isset($_SESSION['usuario_id'])
    && !in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'diagnostico.php'], true)) {
    header('Location: login.php');
    exit;
}
?>