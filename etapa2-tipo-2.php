<?php
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
    $htmlpath = $_GET['link'];
}else{
    header('Location: index.php');
}

$acao = 'buscarhtml';
require 'php/acoes.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etapa 1: <?=$titulo?></title>
    <style>
        body{
            padding: 0;
            margin: 0;
        }
        .esquerda{
            width: 48.45vw;
            height: 95vh;
            float: left;
        }
        .direita{
            width: 50vw;
            min-height: 95vh;
            float: right;
            position: sticky;
            top: 5vh;
            right: 0;
            background-color: silver;
            border: 2px solid dimgray;
        }
        iframe{
            width: 100%;
            height: 80vh;
        }
        .clear{
            clear: both;
        }
        .header{
            background-color: cornflowerblue;
            color: white;
            height: 5vh;
            margin: 0;
        }
        .header a{
            color: white;
            font-size: large;
            text-decoration: none;
        }
        canvas{
            width: 100%;
        }
        .editor{
            width: 100%;
            height: 90vh;
        }
    </style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>



</head>
<body>
    <p class="header"><a href="acessarprojeto.php?titulo=<?=$titulo?>"><-Voltar</a></p>

    <div class="esquerda">
        <!--<iframe src="projetos/<?=$titulo?>/arquivos/<?=$titulo?>.pdf" frameborder="100"></iframe>-->
        <p id="linkdolivro" hidden>projetos/<?=$titulo?>/arquivos/<?=$titulo?>.pdf</p>
        <label for="page-number">Página:</label>
        <input type="number" id="page-number" value="1" min="1">
        <button type="button" onclick="renderPage()">Carregar Página</button> <br>

        <canvas id="pdf-canvas"></canvas>
    </div>
    </form>
    <div class="direita">
        <form action="php/acoes.php?acao=salvarhtml&link=<?=$htmlpath?>&titulo=<?=$titulo?>" method="post">
        <button type="submit" disabled id="salva">Salvar</button>
        <button type="button" onclick="visualizarDiv(1)" id="visualizar">Visualizar</button>
        <button type="button" onchange="readaptar(this)" onclick="visualizarDiv(2)" id="editar">Editar</button>
        <button type="button" onchange="readaptar(this)" onclick="visualizarDiv(3)" id="estilos">Estilos</button><br>

        <iframe src="<?=$htmlpath?>" frameborder="0" id="conteudo"></iframe>
        <textarea class="editor" name="textohtml" id="editorhtml" hidden><?=$htmltxt?></textarea>
        <textarea class="editor" name="editorcss" id="editorcss" hidden></textarea>
        </form>
    </div>

    <script type="text/javascript" src="script/etapa2.js"></script>
    
    
    <div class="clear"></div>

    
</body>
</html>