<?php
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
    $htmlfile = $_GET["html"];
    $htmlpath = "projetos/$titulo/ebook/$htmlfile";
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
        body {
            padding: 0;
            margin: 0;
        }
        .conteudo {
            display: flex;
        }
        .esquerda{
            width: 48.45vw;
            height: 95vh;  
            overflow:scroll;  
        }
        .direita{
            width: 50vw;
            height: 95vh;
            position: sticky;
            top: 5vh;
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
            font-family: monospace;
        }
    </style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

</head>
<body>
    <p class="header"><a href="projeto.php?titulo=<?=$titulo?>"><-Voltar</a></p>
    
    <div class="conteudo">

        <div class="esquerda">
            <p id="linkdolivro" hidden>projetos/<?=$titulo?>/arquivos/<?=$titulo?>.pdf</p>
            <label for="page-number">Página:</label>
            <input type="number" id="page-number" value="1" min="1">
            <button type="button" onclick="mudaPagina('<')">&lArr;</button>
            <button type="button" onclick="mudaPagina('>')">&rArr;</button>
            <button type="button" onclick="renderPage()">Carregar Página</button> <br>

            <canvas id="pdf-canvas"></canvas>
        </div>
        
        <form action="php/acoes.php?acao=salvarhtml&link=<?=$htmlpath?>&titulo=<?=$titulo?>" method="post">
        <div class="direita">
            <button type="submit" id="salva">Salvar</button>
            <button type="button" onclick="visualizarDiv(1)" id="visualizar">Visualizar</button>
            <button type="button" onclick="visualizarDiv(2)" id="editar">Editar</button>
            <button type="button" onclick="visualizarDiv(3)" id="estilos">Estilos</button><br>

            <iframe class="visualizador" name="visor" src="<?=$htmlpath?>" frameborder="0" id="conteudo"></iframe>
            <textarea spellCheck="false" class="editor" name="textohtml" id="editorhtml" hidden><?=$html?></textarea>
            <textarea spellCheck="false" class="editor" name="textocss" id="editorcss" hidden><?=$css?></textarea>
        </div>
        </form>
    
    </div>

    <script type="text/javascript" src="script/etapa2.js"></script>
    
    
    <div class="clear"></div>

    
</body>
</html>