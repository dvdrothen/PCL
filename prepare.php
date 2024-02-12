<?php
//este arquivo é responsável por preparar o ambiente para o PLC funcionar
include_once 'classes/palavras.php';
include_once 'bases/palavras_ignoradas.php';
$p = new Palavras();

$inicio = "Olá, tudo bem? Boa tarde, bom dia";
$resposta = "Olá, em que posso ajudar?";
$mapa_palavras = array();

$mapa_palavras[] = array(
    "id" => 1,
    "frase" => $resposta,
    "chaves" => array_values($p->extrairPalavrasChave($inicio, $palavras_ignoradas)),
    "sinonimos" => array(),
    "principais" => array(
        "olá",
        "boa",
        "tarde",
        "bom",
        "dia"
    ),
    "negativas" => array(
        "não",
        "nada",
        "nunca",
        "jamais",
        "negativo"
    )
);

$frases = $mapa_palavras;


// Convertendo o array modificado de volta para JSON
$frasesJsonAtualizado = json_encode($frases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

//cria um novo arquivo
file_put_contents('frases.json', $frasesJsonAtualizado);
echo "Arquivo frases.json gerado com sucesso!";
