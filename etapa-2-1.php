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
        .corpo {
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
            zoom: 1;
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
        .editor{
            width: 100%;
            height: 90vh;
            font-family: monospace;
        }
        
    </style>

</head>
<body>
    <p class="header"><a href="projeto.php?titulo=<?=$titulo?>"><-Voltar</a></p>
    
    <div class="corpo">

        <div class="esquerda">
            <!--<button type="button" onclick="recarregarIframe()">Recarregar</button> Botão para recarregar recém atualizado arquivo, não funcionou.-->
            <label for="">Zoom:</label>
            <button type="button" onclick="ajustarZoom(1, 'visor')">+</button>
            <button type="button" onclick="ajustarZoom(2, 'visor')">-</button>
            <br>
            <iframe class="visualizador" name="visor" src="<?=$htmlpath?>" frameborder="0" id="visor"></iframe>
        </div>
        
        <form action="php/acoes.php?acao=salvarhtml&link=<?=$htmlpath?>&titulo=<?=$titulo?>" method="post">
        <div class="direita">
            <button type="submit" id="salva">Salvar</button>
            <button type="button" onclick="visualizarEle('html', 'css')">Editar</button>
            <button type="button" onclick="visualizarEle('css', 'html')">Estilos</button>
            <br>
            <textarea spellCheck="false" class="editor" name="textohtml" id="editorhtml"><?=$html?></textarea>
            <textarea spellCheck="false" class="editor" name="textocss" id="editorcss" hidden><?=$css?></textarea>
        </div>
        </form>
    
    </div>

    <script type="text/javascript" src="script/etapa2-1.js"></script>
    
    
    <div class="clear"></div>

    
</body>
</html>