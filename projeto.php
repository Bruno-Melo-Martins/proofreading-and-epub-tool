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
</head>
<body>
    <header>
        <a href="index.php">EPUBER</a>
    </header>
    <p>Data de criação: <?=$data?></p>
    <p>Etapa: <?=$projeto['etapa']?></p>

    <fieldset>
        <legend>Alterar metadados</legend>
        <form action="php/acoes.php?acao=inserirmetadados" method="post" id="form">
            <?php
            $x = 0;
            foreach(array_keys($metadados) as $metadado){
                
            ?>
            <span id="metadado-<?=$x?>">
                <input type="text" name="mt-nm-<?=$x?>" id="mt-nm-<?=$x?>" value="<?=$metadado?>">
                <label>:</label>
                <input type="text" name="mt-ct-<?=$x?>" id="mt-ct-<?=$x?>" value="<?=$metadados[$metadado]?>"> <button type="button">Excluir</button>
            </span> <br>
            <?php
                if(isset($listaMeta)){
                    $listaMeta .= ";metadado-$x";
                }else{
                    $listaMeta = "metadado-$x";
                }
                $x++;
            }
            ?>
            <input type="text" name="lista" id="lista" hidden value="<?=$listaMeta?>">
            <span id="metadado-<?=$x?>">
                <input type="text" name="mt-nm-<?=$x?>" id="mt-nm-<?=$x?>" placeholder="novo">
                <label>:</label>
                <input type="text" name="mt-ct-<?=$x?>" id="mt-ct-<?=$x?>" placeholder="metadado"> 
            </span> <br>
            <button type="submit">Alterar</button>
        </form>
    </fieldset>
    <fieldset>
        <legend>Adicionar imagens</legend>
        <form action="php/inseririmagens" method="post" enctype="multipart/form-data">
            <input type="file" name="imagem" id="imagem" accept="image/*">
        </form>
    </fieldset>

    
</body>
</html>