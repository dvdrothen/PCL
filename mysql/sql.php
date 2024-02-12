<?php
header("Access-Control-Allow-Origin: *");
//permite acesso somente a requisição do dominio e subdominios configurados
$dominio_permitido = "https://bootblocks.com.br"; //configure de acordo com seu site
//use https://bootblocks.com.br para testes no simulador do bootblocks
//use https://bootsites.com.br para um site hospedado no bootblocks
//use https://seusite.com.br para um site hospedado em outro servidor


if (isset($_SERVER['HTTP_ORIGIN'])) { //verifica se o dominio existe
    if ($_SERVER['HTTP_ORIGIN'] != $dominio_permitido) { //verifica se o dominio é igual ao que você configurou
        echo "Acesso negado para " . $_SERVER['HTTP_ORIGIN'];
        exit;
    }
}else{
    header("HTTP/1.1 403 Forbidden");
    exit;
}
$chave_permitida = "123456"; //configure de acordo com chave que você configurou no seu app
$nome_do_banco = "classificados"; //nome do banco de dados
$usuario_do_banco = "root"; //usuario do banco de dados
$senha_do_banco = ""; //senha do banco de dados
$host_do_banco = "localhost"; //host do banco de dados, geralmente localhost ou ip do servidor

//conecta ao banco de dados
$conexao = mysqli_connect($host_do_banco, $usuario_do_banco, $senha_do_banco, $nome_do_banco);
if (mysqli_connect_errno()) {
    echo "Falha ao conectar ao banco de dados: " . mysqli_connect_error();
}

//verifica se a chave de acesso é válida
if (isset($_POST['chave'])){ //verifica se a chave existe
    if($_POST['chave'] == $chave_permitida){ //verifica se a chave é igual a que você configurou
     $tipo = $_POST['tipo']; //SELECT UPDATE DELETE INSERT
    $tabela = $_POST['tabela']; //tabela que será executada a query

    if(isset($_POST['condicao'])){ //verifica se a condição existe
        $condicao = $_POST['condicao']; //condição da query
    }else{
        $condicao = "";
    }
    if(isset($_POST['colunas'])){ //verifica se a colunas existe
        $colunas = $_POST['colunas']; //colunas da query
    }else{
        $colunas = "";
    }
    if(isset($_POST['values'])){ //verifica se a values existe
        $values = $_POST['values']; //valores da query
    }else{
        $values = "";
    }
    if(isset($_POST['coluna_condicao'])){ //verifica se a coluna_condicao existe
        $coluna_condicao = $_POST['coluna_condicao']; //coluna da condição da query
    }else{
        $coluna_condicao = "";
    }
    if(isset($_POST['valor_condicao'])){ //verifica se a valor_condicao existe
        $valor_condicao = $_POST['valor_condicao']; //valor da condição da query
    }else{
        $valor_condicao = "";
    }
    if($tipo == "SELECT"){ //verifica se o tipo é igual a SELECT
        $query = "SELECT * FROM $tabela WHERE  $colunas  $condicao '". $values ."'";
    }else if($tipo == "UPDATE"){ //verifica se o tipo é igual a UPDATE
        foreach ($colunas as $key => $value) {
            $colunas[$key] = $value."='".$values[$key]."'";
        }
        $query = "UPDATE $tabela SET ".implode(",", $colunas)." WHERE $coluna_condicao $condicao '". $valor_condicao ."'";
    }else if($tipo == "DELETE"){ //verifica se o tipo é igual a DELETE
        $query = "DELETE FROM $tabela WHERE $coluna_condicao $condicao '". $valor_condicao ."'";
    }else if($tipo == "INSERT"){ //verifica se o tipo é igual a INSERT
        foreach ($values as $key => $value) {
            $values[$key] = "'".$value."'";
        }
        $query = "INSERT INTO $tabela ($colunas) VALUES (".implode(",", $values).")";

    }

    $resultado = mysqli_query($conexao, $query); //executa a query
      if($resultado){ //verifica se a query foi executada com sucesso
        $dados = array(); //cria um array para armazenar os dados
        while($linha = mysqli_fetch_assoc($resultado)){ //percorre os dados retornados da query
          $dados[] = $linha; //adiciona os dados no array
        }
        echo json_encode($dados); //retorna os dados em formato json
      }else{
        $msg = array(
          'erro' => 'Erro ao executar a query: '.$query //retorna o erro caso a query não seja executada com sucesso
        );
        echo json_encode($msg);
      }
    }else{
        $msg = array(
          'erro' => 'Chave Inválida'
        );
        echo json_encode($msg);
        exit;
    }
}else{
    $msg = array(
          'erro' => 'Chave Inválida'
        );
        echo json_encode($msg);
    exit;
}
?>
