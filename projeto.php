<?php
$acao = 'buscarprojeto';
if(isset($_GET['titulo'])){
    $titulo = $_GET['titulo'];
}else{
    header("Location: index.php");
}
require "php/acoes.php";
//print_r($projeto);
$data = date("j/m/Y",strtotime($projeto['criado']));
$metadados = unserialize($projeto['metadados']);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu projeto: <?=$titulo?></title>
    <style>
        table.imagens, table.imagens td, table.imagens tr{
            border: 1px solid black;
        }

        table.imagens{
            width: 50vw;
            float: left;
        }

        table.imagens tr{
            width: 100%;
        }

        .tdimg{
            width: 20%;
            text-align: center;
            height: 6em;
        }

        .tdimg img{
            max-width: 5em;
            max-height: 5em;
            cursor: pointer;
        }

        .vignetta{
            width: 100%;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.26);
            position: fixed;
            top: 0;
            left: 0;
            padding: 0;
            margin: 0;
        }

        .content {
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            height: 70vh;
            width: 60vw;
            background-color: #FAFAFA;
            border: 1px solid dimgray;
            text-align: center;
            overflow: scroll;
            padding: 2em;
        }

        .form-imgs{
            float: right;
            width: 40%;
        }

        img.miniatura{
            max-width: 2em;
            max-height: 2em;
        }

    </style>
    <script>
        function NovoMetadado(botao){
            var span = document.getElementById("n-meta");
            var checkN =document.getElementById("check-n");
            if(span.hasAttribute("hidden")){
                span.removeAttribute("hidden");
                botao.innerHTML = "Excluir novo Metadado";
                checkN.checked = true;
            }else{
                span.setAttribute("hidden", "");
                botao.innerHTML = "Novo Metadado";
                checkN.checked = false;
            }
        }
        function visualizarImg(img){
            var vignetta = document.getElementById("vignetta");
            var ad = document.getElementById("ad");
            var imagem = img.cloneNode(true);
            imagem.setAttribute("id", "ampliada");

            vignetta.removeAttribute("hidden");
            ad.appendChild(imagem);

            document.body.style.overflow = "hidden";
        }

        function desVisualizarImg(){
            var vignetta = document.getElementById("vignetta");
            var imagem = document.getElementById("ampliada");
            
            imagem.remove();
            vignetta.setAttribute("hidden", "");

            document.body.style.overflow = "scroll";
        }
        function VisualizarIMG(acao){
            var salvar = document.getElementById("salvarimgs");
            var excluir = document.getElementById("excluirimgs");
            if(acao == 1){
                if(salvar.hasAttribute("hidden")){
                    salvar.removeAttribute("hidden");
                }
                if(!excluir.hasAttribute("hidden")){
                    excluir.setAttribute("hidden", "");
                }
            }else{
                if(excluir.hasAttribute("hidden")){
                    excluir.removeAttribute("hidden");
                }
                if(!salvar.hasAttribute("hidden")){
                    salvar.setAttribute("hidden", "");
                }
            }
        }
    </script>
</head>
<body>
    <header>
        <a href="index.php">EPUBER</a>
    </header>
    <p>Data de criação: <?=$data?></p>
    <p>Etapa: <?=$projeto['etapa']?></p>
    <?php
    switch($projeto['etapa']){
        case 1:
    ?>
        <a href="etapa-1.php?titulo=<?=$titulo?>">Editar</a>
    <?php
        break;
    }
    ?>

    <fieldset>
        <legend>Alterar Metadados</legend>
        <form action="php/acoes.php?acao=alterarmetadados&titulo=<?=$titulo?>" method="post">
            <?php
            $metadados = unserialize($projeto['metadados']);
            $x = 0;
            foreach($metadados as $metadado => $valor){
            ?>
            <span>
                <input type="text" name="meta-<?=$x?>" required value="<?=$metadado?>">
                <input type="text" name="valor-<?=$x?>" required value="<?=$valor?>">
                <label for="excluir-<?=$x?>">Excluir</label>
                <input type="checkbox" name="excluir-<?=$x?>" id="excluir-<?=$x?>">
            </span>
            <br>
            <?php
            $x++;
            }
            ?>
            
            <span id="n-meta" hidden >
                <input type="text" name="meta-n" placeholder="Ex.: language">
                <input type="text" name="valor-n" placeholder="Ex.: English">
            </span>

            <button type="button" onclick="NovoMetadado(this)" value="maoi">Novo metadado</button>
            <input type="checkbox" name="check-n" id="check-n" hidden value="novo">

            <br>
            <button type="submit">Alterar</button>
        </form>
    </fieldset>

    <fieldset>
        <legend>Imagens do projeto</legend>
            <table class="imagens">
                <tbody>
                    <?php
                    foreach($images as $image){
                    ?>
                        <tr>
                            <td><?=$image?></td>

                            <td class="tdimg">
                                <img onclick="visualizarImg(this)" src="projetos/<?=$titulo?>/ebook/images/<?=$image?>" alt="<?=$image?>">
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <fieldset class="form-imgs">
                <legend><button onclick="VisualizarIMG(1)">Enviar imagem</button><button onclick="VisualizarIMG(2)">Excluir imagem</button></legend>
                <div id="salvarimgs">
                    <form action="php/acoes.php?acao=salvarimagens&titulo=<?=$titulo?>" method="post" enctype="multipart/form-data">
                        <input type="file" name="imagem" id="imagem" accept="image/*">
                        <label for="nome-imagem">Nome para a imagem</label>
                        <input type="text" name="nome" id="nome">
                        <button type="submit">Enviar</button>
                    </form>
                </div>

                <div hidden id="excluirimgs">
                    <form action="php/acoes.php?acao=excluirimagens&titulo=<?=$titulo?>" method="post">
                        <select name="nome" id="nome" onchange="mudarMiniatura(this)">
                        <?php
                            foreach($images as $image){
                            ?>
                                <option value="<?=$image?>">
                                    <?=$image?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <button onclick="return confirm('Tem certeza que quer excluir?');" type="submit">Excluir</button>
                        <img src="<?="projetos/$titulo/ebook/images/$images[0]"?>" class="miniatura" id="miniatura">
                        
                    </form>
                </div>
            </fieldset>
    </fieldset>

    <script>
        function mudarMiniatura(select){
            var miniatura = document.getElementById("miniatura");
        miniatura.setAttribute("src", '<?="projetos/$titulo/ebook/images/"?>'+select.value);
        }
    </script>

    <div hidden class="vignetta" id="vignetta">
        <div class="content" id="ad">
            <button onclick="desVisualizarImg()">X</button>
            <p>Imagem Ampliada</p>
        </div>
    </div>
</body>
</html>