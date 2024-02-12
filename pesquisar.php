<?php
include_once 'classes/palavras.php';
include_once 'bases/palavras_ignoradas.php';
include_once 'classes/classificador.php';
$p = new Palavras();
$cl = new classificador();



if (isset($_POST['palavra'])) {
    $palavra = $_POST['palavra'];
    $frasesJson = file_get_contents('frases.json');
    $frases = json_decode($frasesJson, true);
    $resposta = $cl->pesquisar($frases, $palavra, $p); //restorna as respostas sem classificar
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa</title>
    <!--LINK CSS-->
    <link rel="stylesheet" type="text/css" href="../css/style-css.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container">
        <?php include_once 'menu.php'; ?>
        <div class="container" style="margin-top: 20px;">
            <h2 class="text-center">Pesquisa por palavra chave</h2>
        </div>
        <div class="container" style="margin-top: 20px;">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="frase" class="form-label">Palavra:</label>
                    <input type="text" <?php if (isset($frase)) {
                                            echo 'value="' . $frase . '"';
                                        } ?> class="form-control" id="palavra" name="palavra">
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
        <?php if (isset($resposta)) { ?>
            <div class="container" style="margin-top: 20px;">
                <h3 class="text-center">Respostas</h3>
                <!-- exibe uma lista de respostas com um botão para escolher a correta -->
                <ul>
                    <?php
                    if (isset($resposta)) {
                        foreach ($resposta as $key => $value) {
                            echo '<li class="list-item">';
                            //mostra botao de editar
                            echo '&nbsp<a href="editar.php?id=' . $value['id'] . '" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i></a>';
                            //btn call function copy
                            echo '&nbsp<button class="btn btn-success btn-sm" onclick="copyToClipboard(\'' . $value['frase'] . '\')"><i class="bi bi-clipboard"></i></button>&nbsp';
                            echo "$value[frase]";

                            echo '</li>';
                        }
                    } else {
                        echo '<li>Nenhuma resposta encontrada</li>';
                    }
                    ?>
                </ul>
            </div>

        <?php } ?>
        <div class="alert"  id="alert"></div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>

</html>
<script>
    //permite apenas uma palavra por vez
    // Restrict input to letters and some special characters only
    $('#palavra').on('keyup', function() {
        //remove tudo apos o primeiro espaco
        var words = $('#palavra').val().split(' ');
        var word = words[0];
        $('#palavra').val(word);

    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => {
                mostrarAlerta("Copiado!");
            })
            .catch((error) => {
                mostrarAlerta("Erro ao copiar!");
            });
    }

    function mostrarAlerta(texto) {
        var alertElement = document.getElementById('alert');
        alertElement.innerHTML = texto;
        alertElement.style.opacity = 1;

        setTimeout(function() {
            alertElement.style.opacity = 0;
        }, 3000); // 3000 milissegundos = 3 segundos
    }

    function copyToClipboard(text) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(text).select();
        document.execCommand("copy");
        $temp.remove();
        mostrarAlerta("Copiado!");
    }
</script>