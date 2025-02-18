<?php
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header('Location: index.php');
}

$acao = 'buscartxtbruto';
require 'php/acoes.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etapa 1: <?=$titulo?></title>
    <style>
        .esquerda{
            width: 50vw;
            float: left;
        }
        h2{
            text-align: center;
        }
        .direita{
            width: 40%;
            float: right;
        }
        .editor{
            overflow: hidden;
            width: 100%;
        }
        .clear{
            clear: both;
        }
    </style>

</head>
<body>
    <p>Vamos fatiar o seu texto! &#128298; Identifique os títulos do seu livro e coloque uma marcação no final da linha de cada um deles. Faça de acordo com a hierarquia de títulos, ou seja: coloque <(H1)> no título principal; <(H2)> nos títulos de capítulos, prefácios etc.; <(H3)> em caso de subtópicos e assim por diante.</p>

    <form action="php/acoes.php?acao=salvartxtbruto" method="post">
    <div><input type="text" disabled name="titulo" id="titulo" value="<?=$titulo?>"><button type="submit">SALVAÊ</button></div>

    <div class="esquerda">
        <textarea class="editor" name="editor" id="editor" oninput='readaptar()' ><?=$texto?></textarea>
        <script type="text/javascript" src="script/etapa1.js"></script>
    </div>
    </form>
    <div class="direita">
        <h2>Aqui estão os títulos do seu arquivo</h2>
        <p>oi</p>
    </div>
    <div class="clear"></div>
</body>
</html>