<?php
if(isset($_GET['nome'])){
    $nome = $_GET['nome'];
}else{
    header('Location: index.php');
}
$acao = 'buscarprojeto';

require 'php/acoes.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$projeto[0]->titulo?></title>
    <style>
        .visualizador span{
            display: block;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <?php
    if($projeto[0]->etapa == 1){
        $fonte = explode(PHP_EOL, $projeto[0]->fonte);
        
    ?>
        <div class="visualizador">
            <?php
                $x = 0;
                foreach($fonte as $linha){
            ?>
                <span id="<?=$x?>"><?=$linha?></span>
            <?php
                $x++;
                }    
            ?>
        </div>
    
    <?php
    }
    ?>
</body>
</html>