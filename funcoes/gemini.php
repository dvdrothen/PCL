<?php 
include_once '../classes/gemini.php';
include '../config.php';    
$g = new gemini(KEY_GEMINI);
$pergunta = $_POST['pergunta'];
$preResposta = $_POST['resposta'];

$promt = "Com base nesta pre resposta: \"
".$preResposta."\",
 formule uma resposta para a pergunta: ".$pergunta.".";

$resposta = $g->generateResponse($promt);
if($resposta != null){
     echo json_encode(array('success' => true, 'resposta' => $resposta));
 }else{
     echo json_encode(array('success' => false, 'resposta' => 'Erro ao gerar resposta'));
 }
; ?>