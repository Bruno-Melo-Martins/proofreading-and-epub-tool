<?php
$acao = 'buscarlista';

require 'php/acoes.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial</title>
    <style>
        form input{
            display:block;
        }
    </style>

    <script>
        //Código para habilitar o input file do pdf
        function habilitarpdf(){
            var tagpdf = document.getElementById("pedefe");
            var tagatt = tagpdf.getAttributeNames();

            if(tagatt.includes("disabled")){
                tagpdf.removeAttribute("disabled");
            }else{
                if(!tagatt.includes("disable")){
                    tagpdf.setAttribute("disabled", "")
                }
            }
            
            
        }
    </script>
</head>
<body>
    <fieldset>
        <legend>Novo projeto</legend>
        <form action="php/acoes.php?acao=criarnovoprojeto" method="post" enctype="multipart/form-data">
            <label for="titulo">Título da obra:</label>
            <input type="text" name="titulo" id="titulo">
            
            <label for="autor">Nome do autor:</label>
            <input type="text" name="autor" id="autor">

            <label for="fonte">Fonte da obra em .txt:</label>
            <input type="file" name="fonte" accept=".txt" id="fonte">

            <label for="tipo">O projeto usará o texto-fonte em .pdf?</label>
            <input type="checkbox" name="tipo" id="tipo" onclick="habilitarpdf()">

            <input type="file" name="pedefe" accept=".pdf" id="pedefe" disabled>

            <button type="submit">Criar</button>
        </form>
    </fieldset>

    <fieldset>
        <legend>Abrir um projeto</legend>
        <?php
            foreach($lista as $projetos){
        ?>
            <a href="acessarprojeto.php?nome=<?=$projetos->nome?>"><div>
                <p>Projeto: <?=$projetos->titulo?></p>
                <p>Criado em: <?=$projetos->criado?></p>    
            </div></a>
        <?php
            }
        ?>
    </fieldset>
</body>
</html>