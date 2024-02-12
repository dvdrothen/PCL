<?php 
include_once 'classes/classificador.php';
$c = new classificador();
//codificação utf-8
header('Content-Type: text/html; charset=utf-8');
$frases = json_decode(file_get_contents('frases.json'), true);
//inverte
$frases = array_reverse($frases);
// Configurações de paginação

$itensPorPagina = 20;
$totalFrases = count($frases);
$totalPaginas = ceil($totalFrases / $itensPorPagina);

if(isset($_GET['id'])){
    $id = $_GET['id'];
    //mostra apenas uma frase
    foreach($frases as $key => $f){
        if($f['id'] == $id){
            $frase = $f;
        }
    }
    $frases = array($frase);
    $totalPaginas = 1;
    $paginaAtual = 1;
    $itensPorPagina = 1;
    $frasesPaginadas = $frases;
}else{
// Determina a página atual
$paginaAtual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$frasesPaginadas = array_slice($frases, $inicio, $itensPorPagina);
}
if(isset($_POST['frase'])){
    $frase = $_POST['frase'];
    $chaves = $_POST['chaves'];
    $sinonimos = $_POST['sinonimos'];
    $principais = $_POST['principais'];
    $negativas = $_POST['negativas'];
    $id = $_POST['id'];
    foreach($frases as $key => $f){
        if($f['id'] == $id){
            $frases[$key]['frase'] = $frase;
            $frases[$key]['chaves'] = explode(", ", $chaves);
            $frases[$key]['sinonimos'] = explode(", ", $sinonimos);
            $frases[$key]['principais'] = explode(", ", $principais);
            $frases[$key]['negativas'] = explode(", ", $negativas);
        }
    }
    $c -> ordenarFrases($frases);
    file_put_contents('frases.json', json_encode($frases, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    echo '<script> alert("Frase editada com sucesso!"); </script>';
    echo '<script> window.location.href = "editar.php"; </script>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Frases</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container">
        <?php include_once 'menu.php'; ?>
        <div class="container" style="margin-top: 20px;">
            <h2 class="text-center">Editar Frases</h2>
        </div>
        <div class="container" style="margin-top: 20px;">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Frase</th>
                        <th scope="col">Chaves</th>
                        <th scope="col">Sinônimos</th>
                        <th scope="col">Precisa conter</th>
                        <th scope="col">Negativas</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($frasesPaginadas as $frase) { ?>
                        <tr>
                            <th scope="row"><?php echo $frase['id']; ?></th>
                            <td><?php echo $frase['frase']; ?></td>
                            <td><?php echo //exibe no maximo 10 chaves
                                implode(", ", array_slice($frase['chaves'], 0, 5)); ?></td>
                            <td><?php echo //exibe no maximo 10 sinonimos
                                implode(", ", array_slice($frase['sinonimos'], 0, 5)); ?></td>
                            <td>
                                <?php echo //exibe no maximo 10 principais
                                implode(", ", array_slice($frase['principais'], 0, 5)); ?>
                            </td>
                            <td>
                                <?php echo //exibe no maximo 10 negativas
                                implode(", ", array_slice($frase['negativas'], 0, 5)); ?>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        <button style="margin-right: 10px;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal<?php echo $frase['id']; ?>">
                                        <i class="bi bi-pencil-square"></i>
                                        </button> &nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="col-6">
                                        <button onclick="deleteFrase(<?php echo $frase['id']; ?>)" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                                    </div>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal" id="modal<?php echo $frase['id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Frase</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="editar.php" method="POST">
                                            <div class="mb-3">
                                                <label for="frase" class="form-label">Frase:</label>
                                                <input type="text" class="form-control" id="frase" name="frase" value="<?php echo $frase['frase']; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="chaves" class="form-label">Chaves:</label>
                                                <input type="text" class="form-control" id="chaves" name="chaves" value="<?php echo implode(", ", $frase['chaves']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="sinonimos" class="form-label">Sinônimos:</label>
                                                <input type="text" class="form-control" id="sinonimos" name="sinonimos" value="<?php echo implode(", ", $frase['sinonimos']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="pontos" class="form-label">Precisa conter:</label>
                                                <input type="text" class="form-control" id="principais" name="principais" value="<?php echo implode(", ", $frase['principais']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label for="negativas" class="form-label">Negativas:</label>
                                                <input type="text" class="form-control" id="negativas" name="negativas" value="<?php echo implode(", ", $frase['negativas']); ?>">
                                            </div>
                                            <input type="hidden" name="id" value="<?php echo $frase['id']; ?>">
                                            <button type="submit" name="atualizar" class="btn btn-primary">Salvar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </tbody>
            </table>

            <!-- Adiciona a navegação da paginação -->
            <nav aria-label="Paginação">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                        <li class="page-item <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    function deleteFrase(id) {
        if (confirm("Deseja realmente deletar esta frase?")) {
            $.ajax({
                url: 'funcoes/deletar.php',
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    alert("Frase deletada com sucesso!");
                    window.location.href = "editar.php?pagina=<?php echo $paginaAtual; ?>";
                }
            });
        }
    }
</script>