<?php

if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header('Location: index.php');
}
$pagina = "projeto.php?titulo=$titulo";


$acao = 'buscartxt';
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
    <form action="php/acoes.php?acao=salvartxt&titulo=<?=$titulo?>" method="post">
        <button type="submit">Salvar</button> 
        <button type="button" class="bt-1" onclick="inserirTag('h1', this)">Título 1</button>
        <button type="button" class="bt-1" onclick="inserirTag('h2', this)">Título 2</button>
        <button type="button" class="bt-1" onclick="inserirTag('h3', this)">Título 3</button>
        <?phpif(isset($_GET['forma']) && $_GET['forma'] == 'poesia'){?>
            <button type="button" class="bt-1" onclick='inserirTag("div class=\"canto\"", this)'>Canto</button>
            <button type="button" class="bt-1" onclick='inserirTag("div class=\"poema\"", this)'>Poema</button>
            <button type="button" class="bt-1" onclick='inserirTag("div class=\"estrofe\"", this)'>Estrofe</button>
        <?php}?>
        <a onclick="return confirm('Tem certeza que quer voltar ao texto original?');" href="php/acoes.php?acao=restaurarbackup&titulo=<?=$titulo?>">Restaurar Backup</a>
    </div>
    
    

    <div class="esquerda">
        <textarea class="editor" name="editor" id="editor" oninput='/*readaptar()*/'><?=$texto?></textarea>
        
    </div>
    </form>
    <div class="direita">
        <h2>Aqui estão os títulos do seu arquivo</h2>
        <?php if(isset($lista)){echo $lista;}?>
        
    </div>

    <script type="text/javascript" src="script/etapa1.js"></script>

</body>
</html>