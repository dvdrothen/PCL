<?php

class classificador
{
    public function classificar($palavras_frase, $frases, $p)
    {
        $resposta = array();
        foreach ($palavras_frase as $palavra) {
            foreach ($frases as $frase) {
                //verifica se possui a palavra no array principais, se não tiver passa para o próximo
                if (count($frase['principais']) > 0) {
                    $possui_principal = false;
                    foreach ($palavras_frase as $plv) {
                        foreach ($frase['principais'] as $prl) {
                            $porcentagem = $p->calcularPorcentagemIgualdade($plv, $prl, 70);
                            //echo $porcentagem ." - palavra: " . $plv . " - principal: " . $prl . "<br>"; //debug
                            if ($porcentagem > 0) {
                                $possui_principal = true;
                            }
                        }
                    }
                    if (!$possui_principal) {
                        continue;
                    }
                }

                //verifica se tem palavras negativas
                if (count($frase['negativas']) > 0) {
                    $possui_negativa = false;
                    foreach ($palavras_frase as $plv) {
                        foreach ($frase['negativas'] as $n) {
                            $porcentagem = $p->calcularPorcentagemIgualdade($plv, $n, 70);
                            if ($porcentagem > 0) {
                                $possui_negativa = true;
                            }
                        }
                    }
                    if ($possui_negativa) {
                        continue;
                    }
                }


                //verifica se possui a palavra no array chaves 
                foreach ($frase['chaves'] as $chave) {
                    $porcentagem = $p->calcularPorcentagemIgualdade($palavra, $chave, 70);
                    if ($porcentagem > 0) {
                        $resposta[] =  array(
                            "frase" => $frase['frase'],
                            "porcentagem" => $porcentagem,
                            "vezes" => "1",
                            "id" => $frase['id']
                        );
                    }
                }
                //verifica se possui a palavra no array sinonimos
                foreach ($frase['sinonimos'] as $sinonimo) {
                    $porcentagem = $p->calcularPorcentagemIgualdade($palavra, $sinonimo, 70);
                    if ($porcentagem > 0) {
                        $resposta[] =  array(
                            "frase" => $frase['frase'],
                            "porcentagem" => $porcentagem,
                            "vezes" => "1",
                            "id" => $frase['id']
                        );
                    }
                }
            }
        }
        //remove frases duplicadas e adiciona a quantidade de vezes que a frase foi encontrada
        $resposta = array_reduce($resposta, function ($acc, $item) {
            $key = $item['frase'];
            if (isset($acc[$key])) {
                $acc[$key]['vezes']++;
            } else {
                $acc[$key] = $item;
            }
            return $acc;
        }, []);
        $resposta = array_values($resposta);
        //soma os pontos + vezes que a frase foi encontrada no array resposta
        foreach ($resposta as &$frase) {
            $frase['vezes'] = $frase['vezes'] + $frase['vezes'];
        }
        //retorna as frases ordenadas por pontos
        usort($resposta, function ($a, $b) {
            return $b['vezes'] - $a['vezes'];
        });
        //se tiver menos de 3 pontos não retorna nada
        $resposta = array_filter($resposta, function ($frase) {
            return $frase['vezes'] >= 3;
        });
        return $resposta;
    }

    public function ordenarFrases($frases)
    {
        //ordena pelo id
        usort($frases, function ($a, $b) {
            return $b['id'] - $a['id'];
        });
        return $frases;
    }

    public function pesquisar($frases, $palavra, $p)
    {
        $resposta = array();
        foreach ($frases as $frase) {
            foreach ($frase['chaves'] as $chave) {
                $porcentagem = $p->calcularPorcentagemIgualdade($palavra, $chave, 70);
                if ($porcentagem > 0) {
                    $resposta[] =  array(
                        "frase" => $frase['frase'],
                        "porcentagem" => $porcentagem,
                        "vezes" => "1",
                        "id" => $frase['id']
                    );
                }
            }
            foreach ($frase['sinonimos'] as $sinonimo) {
                $porcentagem = $p->calcularPorcentagemIgualdade($palavra, $sinonimo, 70);
                if ($porcentagem > 0) {
                    $resposta[] =  array(
                        "frase" => $frase['frase'],
                        "porcentagem" => $porcentagem,
                        "vezes" => "1",
                        "id" => $frase['id']
                    );
                }
            }
        }
        //remove frases duplicadas e adiciona a quantidade de vezes que a frase foi encontrada
        $resposta = array_reduce($resposta, function ($acc, $item) {
            $key = $item['frase'];
            if (isset($acc[$key])) {
                $acc[$key]['vezes']++;
            } else {
                $acc[$key] = $item;
            }
            return $acc;
        }, []);
        $resposta = array_values($resposta);

        return $resposta;
    }
}
