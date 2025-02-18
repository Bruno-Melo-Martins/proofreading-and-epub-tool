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
        .conteudo{
            white-space: pre-line;
            font-family: 'EB Garamond';
            font-size: 14pt;
            font-variant-ligatures: discretionary-ligatures;
            width: 50%;
        }
        .bfl{
            float: left;
            font-size: 24pt;
            
        }
    </style>
    <script>
        function transformar(){
            var texto = document.getElementById('contents').innerHTML;
            var div = document.getElementById('contents');
            var textarea = document.createElement('textarea');
            textarea.setAttribute("id", "editando");
            textarea.setAttribute("style", "width: "+div.offsetWidth+"px; height:"+div.offsetHeight+"px; font-family: monospace;");
            textarea.setAttribute("class", "conteudo");
            textarea.setAttribute("onblur", "salvado()");
            div.replaceWith(textarea);
            textarea.value = texto;
        }

        function salvado(){
            var textarea = document.getElementById('editando');
            let texto = textarea.value;
            var div = document.createElement('div');
            div.setAttribute("id", "contents");
            div.setAttribute("class", "conteudo");
            div.setAttribute('ondblclick', 'transformar()');
            //div.setAttribute("style", "text-align: justify; width: 50%")
            textarea.replaceWith(div);
            div.innerHTML = texto;
        }
        
    </script>
    
</head>
<body>
    <?php
    switch($project['etapa']){
        case 1:
            $caminho = "projetos/$project[titulo]/arquivos/$project[titulo].txt";
            $arquivo = fopen($caminho, 'r');

			$soMinha = new SoMinha();
			$texto = $soMinha->txtparatexto($arquivo);
    ?>
        <!--<h1 id="titulo"><?=$project['titulo']?></h1>-->
        <p id="caminho"><?=$caminho?></p>

        <textarea id="editor" rows="10" cols="50"><?=$texto?></textarea>

        <br>
        <button onclick="salvarTexto()">Salvar</button>
        <br>
        <div class="conteudo" id="contents" ondblclick="transformar()">
<?=$texto?>
        </div>
        
    <?php
    break;
    }
    ?>
</body>
</html>