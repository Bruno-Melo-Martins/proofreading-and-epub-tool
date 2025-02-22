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
    
    <?php switch($etapa){
        case 1:?>
    <p>Parece que você está na Etapa <?=$etapa?>, é hora de analisar o texto bruto e identificar os títulos dentro dele. Cique no link abaixo para começar a trabalhar.</p>

    <p><a href="etapa1.php?titulo=<?=$titulo?>">BOOOORA TRABAIAR</a></p>

    <p>Caso tenha terminado as atividades da primeira etapa clique no link abaixo para seguir para a próxima etapa. ATENÇÃO: você não poderá voltar atrás.</p>
    <p><a onclick="return confirm('Tem certeza que quer ir para a Etapa 2?');" href="php/acoes.php?acao=subiretapa&titulo=<?=$titulo?>">UPGRADE de Etapa</a></p>

    <?php break;
        case 2:?>
        <p>Parece que você está na Etapa <?=$etapa?>, é hora de analisar minuciosamente os textos e formatá-los.</p>
        
        <?php
        $toc = explode(' ? ', $projeto['toc']);
        $x=0;
        echo '<ol>';
            foreach($toc as $t){
                echo '<li>';
                echo "<a href='etapa2-tipo-$projeto[tipo].php?titulo=$titulo&link=projetos/$titulo/ebook/text_ebook_$x.html'>$t</a>";
                echo '</li>';
                $x++;
            }
        echo '</ol>';
        ?>

    <?php break;}?>


</body>
</html>