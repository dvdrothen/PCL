<?php
include_once 'classes/palavras.php';
include_once 'bases/palavras_ignoradas.php';
include_once 'classes/classificador.php';
$p = new Palavras();
$cl = new classificador();

if (isset($_POST['frase'])) {
    $frase = $_POST['frase'];
    $palavras_frase = $p->extrairPalavrasChave($frase, $palavras_ignoradas);
    $frasesJson = file_get_contents('frases.json');
    $frases = json_decode($frasesJson, true);
    $resposta = $cl->classificar($palavras_frase, $frases, $p);
    //verifica se é um array valido
    if (count($resposta) > 0) {
        $primeiraResposta = $resposta[0];
        $primeiraResposta = $primeiraResposta['frase'];
    } else {
        $primeiraResposta = "no";
    }
} else {
    $primeiraResposta = "";
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
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
            <h2 class="text-center">Chat</h2>
        </div>
        <div class="container" style="margin-top: 20px;">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="frase" class="form-label">Pergunte</label>
                    <input type="text" <?php if (isset($frase)) {
                                            echo 'value="' . $frase . '"';
                                        } ?> class="form-control" id="frase" name="frase">
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </div>
        <div class="container" style="margin-top: 20px; display: none;">
            <h3 class="text-center">Respostas</h3>
            <!-- exibe uma lista de respostas com um botão para escolher a correta -->
            <ul>
                <li class="list-item">
                    <p id="resposta">Resposta</p>
                    <button class="btn btn-primary" onclick="copyToClipboard()">Copiar</button>
                </li>
            </ul>
        </div>
    </div>
    <div id="loading" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
        <img src="assets/loading.gif" alt="carregando">
    </div>
    <div class="alert" id="alert"></div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>

</html>
<script>
    function loading(visivel = true) {
        if (visivel) {
            $("#loading").show();
        } else {
            $("#loading").hide();
        }
    }
    var resposta = "<?php echo $primeiraResposta; ?>";

    if (resposta != "") {
        loading();
        $.ajax({
            url: 'funcoes/gemini.php',
            type: 'POST',
            data: {
                pergunta: $("#frase").val(),
                resposta: resposta
            },
            success: function(data) {
                loading(false);
                data = JSON.parse(data);
                if (data.success) {
                    $("#resposta").text(data.resposta);
                    $(".container").show();
                } else {
                    alert("Erro ao gerar resposta");
                }
            }
        });
    }else if(resposta == "no"){
        $("#resposta").text("Não foi possível gerar uma resposta");
    }

    function copyToClipboard() {
        text = $("#resposta").text();
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