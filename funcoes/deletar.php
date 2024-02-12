<?php
$frases = json_decode(file_get_contents('../frases.json'));
if(isset($_POST['id'])){
    $id = $_POST['id'];
    //delete the frase
    foreach($frases as $key => $frase){
        if($frase->id == $id){
            unset($frases[$key]);
        }
    }
    //retira os indices do array
    $frases = array_values($frases);
    file_put_contents('../frases.json', json_encode($frases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
?>