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
$etapa = $projeto['etapa'];
$tipo = $projeto["tipo"];

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
</head>
<body>
    <header>
        <a href="index.php">EPUBER</a>
        <p class="titulo" id="titulo"><?=$titulo?></p>
    </header>
    <p>Data de criação: <?=$data?></p>
    <p>Etapa: <?=$etapa?></p>
    <!--ÁREA PARA IR AO ETAPA-2-->
    <?php
    
    switch($etapa){
        // Editar o txt Etapa 1
        case 1:
    ?>
        <a href="etapa-1.php?titulo=<?=$titulo?>&amp;forma=<?=$projeto['forma']?>">Editar</a>
        <a onclick="return confirm('Tem certeza que quer subir a Etapa?');" href="php/acoes.php?acao=subiretapa&titulo=<?=$titulo?>">Subir Etapa</a>
    <?php
        break;
        // Editar os xhtml Etapa 2
        case 2:
            $toc = unserialize($projeto['toc']);
            echo '<ol>';
            $numero = 0;
            foreach($toc as $xhtml){
                $conteudo = $xhtml['C'];
    ?>
            <li><a href="etapa-2-<?=$tipo?>.php?titulo=<?=$titulo?>&amp;html=text_ebook_<?=$numero?>.xhtml"><?=$conteudo?></a></li>
    <?php
            $numero++;
            }
            echo '</ol>';
            ?>

    <fieldset> <!--CRIAR HTMLZ PARA USAR NO PROGRAMA CALIBRE-->
        <legend>Criar HTMLZ</legend>
        <form action="php/acoes.php?acao=criarhtmlz&amp;titulo=<?=$titulo?>" method="post">
            <?php
            $numero = 0;
            foreach($toc as $xhtml){
                $conteudo = $xhtml['C'];
            ?>
                <label for="text<?=$numero?>"><?=$xhtml['C'];?></label>
                <input type="checkbox" name="text<?=$numero?>" id="text<?=$numero?>">
                <br>
            <?php
            $numero++;
            }
            if(isset($htmlz)){
                echo $htmlz;
            }
            ?>
            <button type="submit">Criar</button>
        </form>
    </fieldset>
            <?php
        break;
    }
    ?>

    <fieldset> <!--ALTERAR METADADOS-->
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
        <legend>Imagens do projeto</legend> <!--ALTERAR IMAGENS-->
            <table class="imagens">
                <tbody>
                    <?php
                    if(isset($images)){
                    foreach($images as $image){
                    ?>
                        <tr>
                            <td><?=$image?></td>

                            <td class="tdimg">
                                <img onclick="visualizarImg(this)" src="projetos/<?=$titulo?>/ebook/<?=$image?>" alt="<?=$image?>">
                            </td>
                        </tr>
                    <?php
                    }
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
                            if(isset($images)){
                            foreach($images as $image){
                            ?>
                                <option value="<?=$image?>">
                                    <?=$image?>
                                </option>
                            <?php
                            }
                            }
                            ?>
                        </select>
                        <button onclick="return confirm('Tem certeza que quer excluir?');" type="submit">Excluir</button>
                        <img src="<?php if (isset($images)) { echo "projetos/$titulo/ebook/$images[0]"; }?>" class="miniatura" id="miniatura">
                        
                    </form>
                </div>
            </fieldset>
    </fieldset>

    <div hidden class="vignetta" id="vignetta"> <!--DIV PARA VISUALIZAR IMAGEM AMPLIADA-->
        <div class="content" id="ad">
            <button onclick="desVisualizarImg()">X</button>
            <p>Imagem Ampliada</p>
        </div>
    </div>

    <script src="script/projeto.js" type="text/javascript"></script>
</body>
</html>