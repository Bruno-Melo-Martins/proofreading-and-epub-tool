<?php
$acao = 'buscarlista';
require "php/acoes.php";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página inicial</title>
    <style>
        form input, form select{
            display:block;
        }
        .duvida{
            border-radius: 100%;
            background-color: black;
            color: white;
        }
    </style>

    <script>
        //Código para habilitar o input file do pdf
        function habilitarpdf(){
            var tagpdf = document.getElementById("pdf");
            var tagatt = tagpdf.getAttributeNames();

            if(tagatt.includes("disabled")){
                tagpdf.removeAttribute("disabled");
                tagpdf.setAttribute("required", "")
            }else{
                if(!tagatt.includes("disable")){
                    tagpdf.setAttribute("disabled", "")
                    tagpdf.removeAttribute("required");
                }
            }
            
            
        }
    </script>
</head>
<body>
    <fieldset>
        <legend>Novo projeto</legend>
        <form action="php/acoes.php?acao=criarprojeto" method="post" enctype="multipart/form-data">
            <label for="titulo">Título da obra:</label>
            <input type="text" name="titulo" id="titulo" required />
            
            <label for="autor">Nome do autor:</label>
            <input type="text" name="autor" id="autor" required />

            <label for="idioma">Idioma (abreviação):</label>
            <input type="text" name="idioma" id="idioma" required />

            <label for="idioma">Forma do texto: <details><summary>Para quê?</summary>Caso o texto seja formatado em parágrafos, iremos formatá-los automaticamente assim, caso o texto seja em verso, não alteraremos sua formatação.</details></label>
            <select name="forma" id="forma" required >
                <option value="prosa">Prosa</option>
                <option value="poesia">Poesia</option>
            </select>

            <label for="fonte">Fonte da obra em .txt:</label>
            <input type="file" name="txt" accept=".txt" id="txt" required />

            <label for="tipo">O projeto usará o texto-fonte em .pdf?</label>
            <input type="checkbox" name="tipo" id="tipo" onclick="habilitarpdf()">

            <input type="file" name="pdf" accept=".pdf" id="pdf" disabled>

            <button type="submit">Criar</button>
        </form>
    </fieldset>

    <fieldset>
        <legend>Abrir um projeto</legend>
        <?php
        if(isset($lista)){
            foreach($lista as $projetos){
        ?>
            <a href="projeto.php?titulo=<?=$projetos['titulo']?>"><div>
                <p>Projeto: <?=$projetos['titulo']?>. Criado em: <?=$projetos['criado']?></p>
            </div></a>
        <?php
            }}
        ?>
    </fieldset>

    <fieldset>
        <legend>Excluir projetos</legend>
        <form action="php/acoes.php?acao=excluirprojeto" method="post" enctype="multipart/form-data">
            <select name="titulo" id="slctexcluir">
                <?php
                    if(isset($lista)){
                        
                        foreach($lista as $projetos){
                ?>
                    <option value="<?=$projetos['titulo']?>">
                        <?=$projetos['titulo']?>
                    </option>

                <?php
                    }}
                ?>
            </select>

            <button onclick="return confirm('Tem certeza que quer excluir?');">Excluir</button>
        </form>
    </fieldset>
</body>
</html>