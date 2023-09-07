<?php

// rename all files from outline and solid folders to the .blade.php format

// Função para processar um arquivo e inserir a variável $attributes
function processarArquivo($caminhoArquivo) {
    $conteudo = file_get_contents($caminhoArquivo);
    $novoConteudo = preg_replace('/<svg /', '<svg {{ $attributes }} ', $conteudo);
    file_put_contents($caminhoArquivo, $novoConteudo);
}

// Pasta onde estão os arquivos .blade.php
$pastaOutline = './outline/';
$pastaSolid = './solid/';

// Loop pelos arquivos na pasta "outline"
$filesOutline = glob($pastaOutline . '*.blade.php');
foreach ($filesOutline as $arquivoOutline) {
    processarArquivo($arquivoOutline);
    echo "Arquivo processado: $arquivoOutline\n";
}

// Loop pelos arquivos na pasta "solid"
$filesSolid = glob($pastaSolid . '*.blade.php');
foreach ($filesSolid as $arquivoSolid) {
    processarArquivo($arquivoSolid);
    echo "Arquivo processado: $arquivoSolid\n";
}

echo "Processamento concluído.\n";