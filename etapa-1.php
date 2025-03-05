<?php
session_start();

if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header('Location: index.php');
}
$pagina = $_SERVER['HTTP_REFERER'];

if(isset($_SESSION['texto']) && $titulo == $_SESSION['texto'][0]){
    $texto = $_SESSION['texto'][1];
}else{
    $acao = 'buscartxt';
    require 'php/acoes.php';
}


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
            width: 45%;
            float: right;
            
            position: sticky;
            top: 26.6pt;
            padding: 5px;
            background-color: silver;
            border: 2px solid dimgray;
        }
        .editor{
            /*overflow: hidden;*/
            width: 100%;
            height: 90vh;
        }
        .clear{
            clear: both;
        }
        .barra{
            position: sticky;
            top: 0;
            padding: 5px;
            background-color: #cae8ca;
            border: 2px solid #4CAF50;
        }
    </style>

</head>
<body>
    <header>
        <a href="<?=$pagina?>"><-Voltar</a>
    </header>
    <details>
        <summary>Vamos fatiar o seu texto! &#128298;</summary>
        <p>Identifique os títulos do seu livro e envelope-os com uma tag de título. Para isso, use tags como <code>&lt;h1&gt;título aqui&lt;/h1&gt;</code>. Obedeça a hierarquia de títulos, apenas um &lt;h1&gt;, para o título principal; em seguida use &lt;h2&gt; para capítulos, prefácio etc.; para eventuais subtópicos dentro de um capítulo (ou seja, dentro de &lt;h2&gt;) use &lt;h3&gt;; O último nível possível é &lt;h6&gt;, não passe disso. Caso tenha dúvidas consulte</p>
    </details>

    <div class="barra">
    <form action="php/acoes.php?acao=salvartxtbruto" method="post">
        <input type="text" hidden name="titulo" id="titulo" value="<?=$titulo?>">
        <button type="submit">SALVAÊ</button> 
        <button type="button" class="bt-1" onclick="inserirTag('h1', this)">Título 1</button>
        <button type="button" class="bt-1" onclick="inserirTag('h2', this)">Título 2</button>
        <button type="button" class="bt-1" onclick="inserirTag('h3', this)">Título 3</button>
    </div>
    
    

    <div class="esquerda">
        <textarea class="editor" name="editor" id="editor" oninput='/*readaptar()*/'><?=$texto?></textarea>
        
    </div>
    </form>
    <div class="direita">
        <h2>Aqui estão os títulos do seu arquivo</h2>
        <?php if(isset($toc)){echo $toc;}?>
        
    </div>
    <div class="clear"></div>

    <script type="text/javascript" src="script/etapa1.js"></script>

</body>
</html>