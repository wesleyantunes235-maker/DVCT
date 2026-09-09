<?php

return array (
  'usuarios' => 
  array (
    0 => 
    array (
      'id' => 1,
      'nome' => 'Administrador',
      'email' => 'admin@dvct.com',
      'senha' => md5('admin123456'),
      'tipo' => 'admin',
      'empresa' => 'DVCT Locações',
    ),
    1 => 
    array (
      'id' => 2,
      'nome' => 'Cliente',
      'email' => 'cliente@cliente.com',
      'senha' => md5('cliente123456'),
      'tipo' => 'cliente',
      'empresa' => 'DVCT Locações',
    ),
  ),
  'equipamentos' => 
  array (
    0 => 
    array (
      'id' => 1,
      'nome' => 'Martelo',
      'preco_diaria' => 20.0,
      'imagem' => 'martelo.svg',
      'descricao' => 'Martelo profissional de aço',
      'quantidade' => 10,
      'codigo' => 'MAR001',
    ),
    1 => 
    array (
      'id' => 2,
      'nome' => 'Serra Manual',
      'preco_diaria' => 15.0,
      'imagem' => 'serra.svg',
      'descricao' => 'Serra manual para madeira',
      'quantidade' => 8,
      'codigo' => 'SER001',
    ),
    2 => 
    array (
      'id' => 3,
      'nome' => 'Britadeira',
      'preco_diaria' => 50.0,
      'imagem' => 'britadeira.svg',
      'descricao' => 'Britadeira elétrica profissional',
      'quantidade' => 5,
      'codigo' => 'BRI001',
    ),
    3 => 
    array (
      'id' => 4,
      'nome' => 'Parafusadeira',
      'preco_diaria' => 60.0,
      'imagem' => 'parafusadeira.svg',
      'descricao' => 'Parafusadeira sem fio 18V',
      'quantidade' => 7,
      'codigo' => 'PAR001',
    ),
    4 => 
    array (
      'id' => 5,
      'nome' => 'Esmerilhadeira',
      'preco_diaria' => 57.0,
      'imagem' => 'esmerilhadeira.svg',
      'descricao' => 'Esmerilhadeira angular 4.5"',
      'quantidade' => 6,
      'codigo' => 'ESM001',
    ),
    5 => 
    array (
      'id' => 6,
      'nome' => 'Esquadro',
      'preco_diaria' => 10.0,
      'imagem' => 'esquadro.svg',
      'descricao' => 'Esquadro de pedreiro 60cm',
      'quantidade' => 15,
      'codigo' => 'ESQ001',
    ),
    6 => 
    array (
      'id' => 7,
      'nome' => 'Betoneira',
      'preco_diaria' => 90.0,
      'imagem' => 'betoneira.svg',
      'descricao' => 'Betoneira CSM Max 400L',
      'quantidade' => 4,
      'codigo' => 'BET001',
    ),
    7 => 
    array (
      'id' => 8,
      'nome' => 'Andaime',
      'preco_diaria' => 100.0,
      'imagem' => 'andaime.svg',
      'descricao' => 'Andaime profissional tubular',
      'quantidade' => 20,
      'codigo' => 'AND001',
    ),
    8 => 
    array (
      'id' => 9,
      'nome' => 'Andaime teste',
      'quantidade' => 18,
      'codigo' => 'AND0033',
      'preco_diaria' => 99.98,
      'imagem' => '6aa0b691eea75.svg',
      'descricao' => 'Equipamento cadastrado pelo admin',
    ),
  ),
  'solicitacoes' => 
  array (
    0 => 
    array (
      'id' => 1,
      'usuario_id' => 2,
      'equipamento_id' => 6,
      'dias' => 2,
      'quantidade' => 15,
      'valor_total' => 300.0,
      'status' => 'ativo',
      'data_solicitacao' => '2026-09-09 01:26:36',
      'data_inicio' => '2026-09-09 01:27:34',
      'data_fim' => '2026-09-11 01:27:34',
    ),
    1 => 
    array (
      'id' => 2,
      'usuario_id' => 1,
      'equipamento_id' => 9,
      'dias' => 6,
      'quantidade' => 2,
      'valor_total' => 1199.76,
      'status' => 'solicitado',
      'data_solicitacao' => '2026-09-09 01:30:26',
      'data_inicio' => NULL,
      'data_fim' => NULL,
    ),
  ),
);
