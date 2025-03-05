<?php
$acao = 'buscarprojeto';
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header("Location: index.php");
}
require "php/acoes.php";
print_r($projeto);
$data = date("j/m/Y",strtotime($projeto['criado']));
$metadados = unserialize($projeto['metadados']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu projeto: <?=$titulo?></title>
    <script>
        function NovoMetadado(botao){
            var span = document.getElementById("n-meta");
            var checkN =document.getElementById("check-n");
            if(span.hasAttribute("hidden")){
                span.removeAttribute("hidden", "");
                botao.innerHTML = "Excluir novo Metadado";
                checkN.checked = true;
            }else{
                span.setAttribute("hidden", "");
                botao.innerHTML = "Novo Metadado";
                checkN.checked = false;
            }
        }
    </script>
</head>
<body>
    <header>
        <a href="index.php">EPUBER</a>
    </header>
    <p>Data de criação: <?=$data?></p>
    <p>Etapa: <?=$projeto['etapa']?></p>
    <?php
    switch($projeto['etapa']){
        case 1:
    ?>
        <a href="etapa-1.php?titulo=<?=$titulo?>">Editar</a>
    <?php
        break;
    }
    ?>

    <fieldset>
        <legend>Alterar Metadados</legend>
        <form action="php/acoes.php?acao=alterarmetadados&titulo=<?=$titulo?>" method="post">
            <?php
            $metadados = unserialize($projeto['metadados']);
            $x = 0;
            foreach($metadados as $metadado => $valor){
            ?>
            <span>
                <input type="text" name="meta-<?=$x?>" required value="<?=$metadado?>">
                <input type="text" name="valor-<?=$x?>" required value="<?=$valor?>">
                <label for="excluir-<?=$x?>">Excluir</label>
                <input type="checkbox" name="excluir-<?=$x?>" id="excluir-<?=$x?>">
            </span>
            <br>
            <?php
            $x++;
            }
            ?>
            
            <span id="n-meta" hidden >
                <input type="text" name="meta-n" placeholder="Ex.: language">
                <input type="text" name="valor-n" placeholder="Ex.: English">
            </span>

            <button type="button" onclick="NovoMetadado(this)" value="maoi">Novo metadado</button>
            <input type="checkbox" name="check-n" id="check-n" hidden value="novo">

            <br>
            <button type="submit">Alterar</button>
        </form>
    </fieldset>

    
</body>
</html>