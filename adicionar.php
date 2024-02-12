<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treinamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <?php include_once 'menu.php'; ?>
        <div class="container" style="margin-top: 20px;">
            <h2 class="text-center">Adicionar Pergunta</h2>
        </div>
        <div class="container" style="margin-top: 20px;">
            <form action="" method="post"> 
                <div class="mb-3">
                    <label for="pergunta" class="form-label">Insira a pergunta</label>
                    <textarea class="form-control" rows="3" id="pergunta" name="pergunta"></textarea>
                </div>
                <div class="mb-3">
                    <label for="pergunta" class="form-label">Para dar a resposta, a pergunta recisa conter a (as) palavras:</label>
                    <input type="text" class="form-control" id="principais" name="principais" placeholder="palavra1, palavra2, palavra3">
                </div>
                <div class="mb-3">
                    <label for="texto" class="form-label">a pergunta NÃO deve conter:</label>
                    <input type="text" class="form-control" id="negativas" name="negativas" placeholder="palavra1, palavra2, palavra3">
                </div>
                <div class="mb-3">
                    <label for="texto" class="form-label">Insira a resposta</label>
                    <textarea class="form-control" rows="3" id="texto" name="texto"></textarea>
                </div>
                <button type="submit" id="enviar" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>
    <!-- make a loading -->
    <div id="loading" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
    <img src="assets/loading.gif" alt="carregando">
</div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>

</html>
<script>
    $(document).ready(function() {

        function loading(visivel = true) {
            if (visivel) {
                $("#loading").show();
            } else {
                $("#loading").hide();
            }
        }
        $("#enviar").click(function(event) {
            event.preventDefault();
            var pergunta = $("#pergunta").val();
            var texto = $("#texto").val();
            var principais = $("#principais").val();
            var negativas = $("#negativas").val();
            loading();
            $.ajax({
                url: "funcoes/add_frase.php",
                type: "post",
                data: {
                    pergunta: pergunta,
                    texto: texto,
                    principais: principais,
                    negativas: negativas
                },
                success: function(data) {
                    loading(false);
                    console.log(data);
                    if (data == "success") {
                        alert("Adicionado com sucesso");
                        //recarrega a pagina
                        location.reload();
                    } else {
                        alert("Erro ao adicionar");
                    }
                }
            });
        });
    });
</script>