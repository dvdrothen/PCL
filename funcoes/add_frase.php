<?php 
if (isset($_POST['pergunta']) && !empty($_POST['texto'])) {
    include_once '../classes/palavras.php';
    include_once '../classes/classificador.php';
    include_once '../bases/palavras_ignoradas.php';
    $p = new Palavras();
    $c = new classificador();

    $texto = $_POST['pergunta'];
    $resposta = $_POST['texto'];
    $principais = $_POST['principais'];
    $negativas = $_POST['negativas'];
    $mapa_palavras = array();
    $id = 0;
    $frases_atual = file_get_contents('../frases.json');
    $frases_at_array = json_decode($frases_atual, true);
    //pega o ultimo id
    foreach ($frases_at_array as $frase) {
        if ($frase['id'] > $id) {
            $id = $frase['id'];
        }
    }

    $id++; //soma 1 no id
    //converte para tokens
    $tokens = array_values($p->extrairPalavrasChave($texto, $palavras_ignoradas));

    $sinonimos_fim = array();
    $mapa_palavras[] = array( //adiciona a pergunta
        "id" => $id,
        "frase" => $resposta,
        "chaves" => $tokens,
        "sinonimos" => $sinonimos_fim,
        "principais" => explode(", ", $principais),
        "negativas" => explode(", ", $negativas)
    );

    $frases = array_merge($frases_at_array, $mapa_palavras);
    $c ->ordenarFrases($frases);
    $frasesJson = json_encode($frases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents('../frases.json', $frasesJson);
    echo "success";
}

?>