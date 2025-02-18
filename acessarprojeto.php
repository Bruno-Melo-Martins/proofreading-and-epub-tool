<?php
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header('Location: index.php');
}

$acao = 'buscarprojeto';
require 'php/acoes.php';

//print_r($projeto);
$projeto = $projeto[0];
//print_r($project);

$etapa = $projeto['etapa'];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?="Projeto: $projeto[titulo]"?></title>
    <style>
    </style>
</head>
<body>
    <p><a href="index.php"><-Voltar</a></p>
    <?php
    $time = strtotime($projeto['criado']);
    $data = date("j \of F \of Y",$time);
    $tarefa = new SoMinha;
    $datinha = $tarefa->conversordeData($data);

    ?>
    <h1>Bem vindo ao seu projeto: <?=$projeto['titulo']?>!</h1>
    <p>Seu projeto foi criado em <?=$datinha?></p>
    <p>Parece que você está na Etapa <?=$etapa?>, <?php if($etapa == 1){?>é hora de analisar o texto bruto e identificar os títulos dentro dele. Cique no link abaixo para começar a trabalhar.<?php }?></p>

    <?php if($etapa == 1){?>
    <a href="etapa1.php?titulo=<?=$titulo?>">BOOOORA TRABAIAR</a>
    <?php }?>
</body>
</html>