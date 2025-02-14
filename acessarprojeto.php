<?php
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header('Location: index.php');
}

$acao = 'buscarprojeto';
require 'php/acoes.php';

//print_r($projeto);
$project = $projeto[0];
//print_r($project);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?="Etapa $project[etapa]: $project[titulo]"?></title>
    <style>
        .visualizador span{
            display: block;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <?php
    if($project['etapa'] == 1){
        
        
    ?>
        <h1 id="titulo"><?=$project['titulo']?></h1>
        <h2>Editor de Texto</h2>
    
    
    <?php
    }
    ?>
</body>
</html>