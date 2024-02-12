<?php

class Palavras
{
    public function extrairPalavrasChave($frase, $palavras_ignoradas = array())
    {
        // Transforma a frase em array de palavras
        $palavras = explode(' ', $frase);

        //coloca tudo em minúsculas
        $palavras = array_map('strtolower', $palavras);
        // Remove palavras desnecessárias, como artigos e preposições
        $palavrasFiltradas = array_diff($palavras, $palavras_ignoradas);
        // Retorna as palavras filtradas
        //remove palavras vazias
        $palavrasFiltradas = array_filter($palavrasFiltradas);
        //remove : , . ! ?
        $palavrasFiltradas = array_map(function ($palavra) {
            return preg_replace('/[.,:!?]/', '', $palavra);
        }, $palavrasFiltradas);
        return $palavrasFiltradas;
    }

    public function extrairFrases($texto)
    {
        // Transforma o texto em array de frases e inclui pontos de exclamação e interrogação
        $frases = preg_split('/(?<=[.?!])\s+/', $texto);
        // Retorna as frases
        return $frases;
    }

    public function calcularPorcentagemIgualdade($palavra1, $palavra2, $minimo = 0)
{
    // Converte as palavras para minúsculas para tornar a comparação case-insensitive
    $palavra1 = strtolower($palavra1);
    $palavra2 = strtolower($palavra2);
    //remove : , . ! ?
    $palavra1 = preg_replace('/[.,:!?]/', '', $palavra1);
    $palavra2 = preg_replace('/[.,:!?]/', '', $palavra2);
    //remove espaços
    $palavra1 = str_replace(' ', '', $palavra1);
    $palavra2 = str_replace(' ', '', $palavra2);

    //remove acentos
    $palavra1 = $this->removeAcentos($palavra1);
    $palavra2 = $this->removeAcentos($palavra2);

    $tamanho1 = strlen($palavra1);
    $tamanho2 = strlen($palavra2);

    // Usar o tamanho da palavra mais longa
    $tamanhoMaior = max($tamanho1, $tamanho2);

    $igualdade = 0;

    if ($tamanhoMaior == 0) {
        return 0;
    }

    // Compara as letras em cada posição até o tamanho maior
    for ($i = 0; $i < $tamanhoMaior; $i++) {
        // Verifica se a posição existe em ambas as palavras antes de comparar
        if ((isset($palavra1[$i]) && isset($palavra2[$i])) && $palavra1[$i] === $palavra2[$i]) {
            $igualdade++;
        }
    }

    // Calcular porcentagem de igualdade em relação ao maior tamanho
    $porcentagem = ($igualdade / $tamanhoMaior) * 100;

    if ($porcentagem >= $minimo) {
        return $porcentagem;
    } else {
        return 0;
    }
}


    public function removeAcentos($string)
    {
        $string = preg_replace("/[áàâãä]/", "a", $string);
        $string = preg_replace("/[ÁÀÂÃÄ]/", "A", $string);
        $string = preg_replace("/[éèêë]/", "e", $string);
        $string = preg_replace("/[ÉÈÊË]/", "E", $string);
        $string = preg_replace("/[íìîï]/", "i", $string);
        $string = preg_replace("/[ÍÌÎÏ]/", "I", $string);
        $string = preg_replace("/[óòôõö]/", "o", $string);
        $string = preg_replace("/[ÓÒÔÕÖ]/", "O", $string);
        $string = preg_replace("/[úùûü]/", "u", $string);
        $string = preg_replace("/[ÚÙÛÜ]/", "U", $string);
        $string = preg_replace("/[ç]/", "c", $string);
        $string = preg_replace("/[Ç]/", "C", $string);
        $string = preg_replace("/[ñ]/", "n", $string);
        $string = preg_replace("/[Ñ]/", "N", $string);
        return $string;
    }
}
