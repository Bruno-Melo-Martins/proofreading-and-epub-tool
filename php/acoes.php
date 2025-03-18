<?php

require "modelo.php";
require "service.php";
require "conexao.php";

// Default
$VoltarAPaginaAnterior = false;

// Receber as principais strings do projeto
if(isset($_GET['acao'])){
	$acao = $_GET['acao'];
}

if(isset($_POST['titulo'])){
	$titulo = $_POST['titulo'];
}elseif(isset($_GET['titulo'])){
	$titulo = $_GET['titulo'];
}

if(isset($_SERVER['HTTP_REFERER'])){
	$pagAnterior = $_SERVER['HTTP_REFERER'];
}

switch($acao){
	/* CONECTADO COM O PRIMEIRO FORMULÁRIO */
	case 'criarprojeto':
		$VoltarAPaginaAnterior = true;

		// Declarar variáveis
		$autor = $_POST['autor'];
		$idioma = $_POST['idioma'];
		$forma = $_POST['forma'];
		$txt = $_FILES['txt']['tmp_name'];
		// Para verificar os elementos
		//$elementos = ['titulo','autor', 'idioma', 'forma'];
		$arquivos[] = 'txt';


		// Declarar tipo:
		if(isset($_POST['tipo'])){
			$tipo = 2;
			$pdf = $_FILES['pdf']['tmp_name'];
			$arquivos[] = 'pdf';
		}else{
			$tipo = 1;
		}

		// Verificar elementos NÃO FUNCIONOU
		/*$soMinha = new SoMinha();
		$tudocerto = $soMinha->verificarElementos($elementos, $arquivos);
		if($tudocerto == false){
			$erro = 'Elementos invalidos';
			break;
		}*/

		// Verificar se há títulos iguais em tb_projetos:
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set("titulo", $titulo);
			$bancoDados = new BancoDados($conexao, $tarefa);
			$numeroLinhas = $bancoDados->verificarTitulos();
			//print_r($numeroLinhas[0]['COUNT(*)']);
			if($numeroLinhas[0]['COUNT(*)'] != 0){
				$erro = 'Projeto ja existe';
				break;
			}
		
		// Criar pastas do projeto:
		$pastas = ["../projetos/$titulo", "../projetos/$titulo/arquivos", "../projetos/$titulo/backup", "../projetos/$titulo/ebook"];

		$soMinha = new SoMinha();
		$soMinha->criarPastas($pastas);

		$pastas = [];

		// Mover arquivos para pastas
		move_uploaded_file($txt, "../projetos/$titulo/arquivos/$titulo.txt");
		copy("../projetos/$titulo/arquivos/$titulo.txt", "../projetos/$titulo/backup/$titulo.txt");
		if($tipo == 2){
		move_uploaded_file($pdf, "../projetos/$titulo/arquivos/$titulo.pdf");
		}

		// Adicionar o primeiro metadado:
		$metadados = serialize(["author" => $autor]);

		// Inserir projeto em tb_projetos
		$tarefa = new Tarefa();
		$conexao = new Conexao();
		$tarefa->__set("titulo", $titulo);
		$tarefa->__set("autor", $autor);
		$tarefa->__set("idioma", $idioma);
		$tarefa->__set("forma", $forma);
		$tarefa->__set("tipo", $tipo);
		$tarefa->__set("metadados", $metadados);
		$bancoDados = new BancoDados($conexao, $tarefa);
		$bancoDados->inserirprojeto();
		break;

		case 'buscarlista':
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefaService = new BancoDados($conexao, $tarefa);
			$lista = $tarefaService->buscarlista();
		break;

		case 'excluirprojeto':
			$VoltarAPaginaAnterior = true;

			// Excluir pastas e subpastas
			$caminho = "../projetos/$titulo";
			$soMinha = new SoMinha();
			$soMinha->deletarPasta($caminho);

			// Excluir linha da tabela
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$tarefaService->excluirprojeto();

		break;
		/* CONECTADO EM PÁGINA PROJETO.PHP */
		case 'buscarprojeto':
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto()[0];

			// Buscar imagens
			$caminho = "./projetos/$titulo/ebook";
			$arquivos = array_diff(scandir($caminho, 1), ['.', '..']);

			foreach($arquivos as $arquivo){
				if(is_file("$caminho/$arquivo")){
					if(getimagesize("$caminho/$arquivo")){
						$images[] = $arquivo;
					}
				}
			}

			// Verificar se há htmlz ou epub
			$arquivo = "projetos/$titulo/arquivos/$titulo.htmlz";
			if(file_exists("./$arquivo")){
				$htmlz = "<a class=\"download\" download=\"$titulo.htmlz\" href=\"$arquivo\">Baixar</a>";
			}
			$arquivo = "projetos/$titulo/arquivos/$titulo.epub";
			if(file_exists("./$arquivo")){
				$epub = "<a class=\"download\" download=\"$titulo.epub\" href=\"$arquivo\">Baixar</a>";
			}
			
		break;
		/* CONECTADO EM PÁGINA PROJETO.PHP */
		case 'alterarmetadados':
			$VoltarAPaginaAnterior = true;
			// Checar cada um dos possíveis metadados
			$y = 0;
			$a = false;
			while($a == false){
				if(isset($_POST["excluir-$y"])){
					$y++;
					echo "não";
				}else{
					if(!isset($_POST["meta-$y"]) && $_POST["meta-$y"] == ''){
						$a = true;
					}else{
						$meta[$_POST["meta-$y"]] = $_POST["valor-$y"];
						$y++;
					}
					}
				}
			

			if(isset($_POST['check-n']) && $_POST['meta-n'] != '' && $_POST['valor-n'] != ''){
				$meta[$_POST["meta-n"]] = $_POST["valor-n"];
			}

			$metadados = serialize($meta);

			// Adicionar novo vetor serializado na tabela
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefa->__set('valor', $metadados);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$tarefaService->inserirMeta();

		break;
		/* CONECTADO COM ETAPA-1.PHP */
		case 'buscartxt':
			// Pegar texto
			$caminho = "./projetos/$titulo/arquivos/$titulo.txt";
			$txt = fopen($caminho, "r");
			$soMinha = new SoMinha();
			$texto = $soMinha->txtparatexto($txt);

			// Pegar toc do BD
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto()[0];

			// Criar lista como o toc
			if($projeto['toc'] != ''){
				$titles = unserialize($projeto['toc']);
				// Lista sem links de navegação
				$links = false;
				$soMinha = new SoMinha();
				$lista = $soMinha->tocParaLista($titles, $links);
			}

			break;
		case 'salvartxt':	
			$VoltarAPaginaAnterior = true;
			$caminho = "../projetos/$titulo/arquivos/$titulo.txt";
			$txt = $_POST['editor'];

			$txt = str_replace("&", "&amp;", $txt);

			$txtvet = explode("\n", $txt);
						
			
			foreach($txtvet as $linha){
				for($x = 1; $x <= 6; $x++){
					if(str_contains($linha, "<h$x>") && str_contains($linha, "</h$x>")){
						$title = str_replace("<h$x>", "", $linha);
						$title = trim(str_replace("</h$x>", "", $title));
						$titles[] = ["H" => $x, "C" => $title];
					}
				}	
			}
			

			file_put_contents($caminho, $txt);

			$toc = serialize($titles);

			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefa->__set('valor', $toc);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$tarefaService->inserirToc();
			break;
		/* CONECTADO COM PROJETO.PHP */
		case 'salvarimagens':
			$VoltarAPaginaAnterior = true;
			$image = $_FILES['imagem']['name'];
			$extension = pathinfo($image, PATHINFO_EXTENSION);
			
			if(isset($_POST['nome']) && $_POST['nome'] != ''){
				$nome = strtolower("$_POST[nome].$extension");
			}else{
				$nome = strtolower(pathinfo($image, PATHINFO_BASENAME));
			}

			$caminho = "../projetos/$titulo/ebook/$nome";
			move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
		break;

		case 'excluirimagens':
			$VoltarAPaginaAnterior = true;
			$caminho = "../projetos/$titulo/ebook/$_POST[nome]";
			unlink($caminho);
		break;
		case 'restaurarbackup':
			$VoltarAPaginaAnterior = true;
			$backup = "../projetos/$titulo/backup/$titulo.txt";
			$arquivo = "../projetos/$titulo/arquivos/$titulo.txt";

			// Pegar texto do backup
			$txt = fopen($backup, "r");
			$soMinha = new SoMinha();
			$texto = $soMinha->txtparatexto($txt);

			// Substituir texto e apagar toc
			file_put_contents($arquivo, $texto);

			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefa->__set('valor', '');
			$tarefaService = new BancoDados($conexao, $tarefa);
			$tarefaService->inserirToc();
		break;
		/* MUDAR ETAPA DE 1 PARA 2 */
		case 'subiretapa':
			// Buscar projeto
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto()[0];
			
			// Declarar variáveis
			$etapa = (int)$projeto['etapa'];
			$tipo = (int)$projeto['tipo'];
			$forma = $projeto['forma'];
			$toc = $projeto['toc'];
			$idioma = $projeto['idioma'];
			$metadados = $projeto['metadados'];
			if($etapa != 1){
				break;
			}
			// Pegar o texto
			/*$caminho = "../projetos/$titulo/arquivos/$titulo.txt";
			$txt = fopen($caminho, "r");
			$soMinha = new SoMinha();
			$texto = $soMinha->txtparatexto($txt);*/

			// Dividir xhtml
			$soMinha = new SoMinha();
			$soMinha->dividirxhtml($titulo, $forma, $idioma);

			// Criar style.css
			$txtstyle = "body{\n	margin: 0;\n	padding: 0 10%;\n}\n.titulo{\n	text-align: center;\n}\n.paragrafo{\n	text-indent: 1em;\n	text-align: justify;\n}\n.paragrafo_capitular{\n	text-indent: 0;\n	text-align: justify;\n}\n.numero_pagina{\n	position: absolute;\n	left: 94%;\n	font-size: 0.7em;\n	text-indent: 0;\n}\n.imagem_centro{\n	display: block;\n	margin-left: auto;\n	margin-right: auto;\n}\n.versalete{\n	font-variant: small-caps;\n}\n.capitular1{\n	float: left;\n	height: 20em;\n}\n.clear{\n	clear: both;\n}";
			$caminho = "../projetos/$titulo/ebook/style.css";
			if($forma == 'poesia'){
				$txtstyle .= "\ndiv.canto{\n\twhite-space: pre;\n\ttext-indent:-1em;\n\tmargin-right: 1em;\n}\np.verso{\n\tmargin: 0;\n\tpadding: 0;\n}";
			}
			$style = fopen($caminho, "w");
			fwrite($style, $txtstyle);
			fclose($style);

			// Criar nav.xhtml (toc)
			$soMinha = new SoMinha();
			$soMinha->criartoc($titulo, $toc);

			// Subir etapa no Banco de dados
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$tarefaService->subiretapa();

			header("Location: ../projeto.php?titulo=$titulo");

		break;
		/* CONECTADO COM ETAPA-2.PHP */
		case 'buscarhtml':
			$caminho = "./projetos/$titulo/ebook/$htmlfile";
			$arquivo = fopen($caminho, 'r');
			$soMinha = new SoMinha();
			$html = $soMinha->txtparatexto($arquivo);

			$cssfile = fopen("./projetos/$titulo/ebook/style.css", "r");

			$soMinha = new SoMinha();
			$css = $soMinha->txtparatexto($cssfile);
		break;
		case 'salvarhtml':
			$VoltarAPaginaAnterior = true;
			$link = "../$_GET[link]";
			$texto = $_POST['textohtml'];
			$textocss = $_POST['textocss'];
			$css = "../projetos/$titulo/ebook/style.css";

			$texto = str_replace("&", "&amp;", $texto);

			//echo "$texto <br> $textocss";
			file_put_contents($link, $texto);
			file_put_contents($css, $textocss);

		break;
		case 'criarhtmlz':
			$VoltarAPaginaAnterior = true;
			// Buscar projeto
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto()[0];
			$toc = unserialize($projeto['toc']);
			$idioma = $projeto['idioma'];
			$metadados = unserialize($projeto['metadados']);
			$autor = $projeto['autor'];

			// Criar a pasta htmlz
			$pastas = ["../projetos/$titulo/htmlz"];
			$soMinha = new SoMinha();
			$soMinha->criarPastas($pastas);

			// Criar index.html
			$x = 0;
			foreach($toc as $title){
				if(isset($_POST["text$x"]) && $_POST["text$x"] == 'on'){
					$textos[] = $x;
				}
				$x++;
			}

			$soMinha = new SoMinha();
			$soMinha->criarHtmlz($textos, $titulo, $idioma, $metadados);

			// Buscar imagens
			$caminho = "../projetos/$titulo/ebook";
			$arquivos = array_diff(scandir($caminho, 1), ['.', '..']);

			foreach($arquivos as $arquivo){
				if(is_file("$caminho/$arquivo")){
					if(getimagesize("$caminho/$arquivo")){
						$images[] = $arquivo;
					}
				}
			}


			// Copia as imagens da pasta ebook para htmlz e verifica se há imagem de capa
			$capa = "";
			foreach($images as $image){
				copy("$caminho/$image", "../projetos/$titulo/htmlz/$image");
				if(pathinfo("$caminho/$image", PATHINFO_FILENAME) == "cover"){
					$extensao = pathinfo("$caminho/$image", PATHINFO_EXTENSION);
					$capa = "cover.$extensao";
				}
			}

			$caminho = "../projetos/$titulo/ebook/style.css";
			copy($caminho, "../projetos/$titulo/htmlz/style.css");

			// Criar metadata.opf
			$soMinha = new SoMinha();
			$soMinha->criarMetadataOPF($capa,$titulo, $idioma, $metadados, $autor);

			// Criar htmlz e disponibilizá-lo para download:
			$pasta = "../projetos/$titulo/htmlz/";
			$soMinha = new SoMinha();
			$soMinha->zipHtmlz($pasta, $titulo);

			
			// Excluir a pasta htmlz
			$caminho = "../projetos/$titulo/htmlz";
			$soMinha = new SoMinha();
			$soMinha->deletarPasta($caminho);
		break;
		case "criarepub":
			$VoltarAPaginaAnterior = true;
			// Buscar projeto
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set('titulo', $titulo);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto()[0];
			$toc = unserialize($projeto['toc']);
			$idioma = $projeto['idioma'];
			$metadados = unserialize($projeto['metadados']);
			$autor = $projeto['autor'];

			// Criar a pasta epub e subpastas
			$pastas = ["../projetos/$titulo/epub"];
			$soMinha = new SoMinha();
			$soMinha->criarPastas($pastas);

			// Conseguir as imagens de ebook
			$caminho = "../projetos/$titulo/ebook";
			$arquivos = array_diff(scandir($caminho, 1), ['.', '..']);

			// Copiar arquivos para pasta epub
			$cover = false;
			foreach($arquivos as $arquivo){
				$file = "$caminho/$arquivo";
				if(is_file($file)){
					copy($file, "../projetos/$titulo/epub/$arquivo");
					// Verificar se há um arquivo como nome cover e se é uma imagem
					if(pathinfo($file, PATHINFO_FILENAME) == "cover" && getimagesize($file)){
						$capa = true;
						$capa_basename = pathinfo($file, PATHINFO_BASENAME);
					}
					$files[] = $file;
				}
			}

			// Criar cover.xhtml:
			if($capa){
				$covertxt = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n\t<title>Capa</title>\n</head>\n<body>\n\t<div style=\"text-align:center;\">\n\t\t<img src=\"$capa_basename\" alt=\"Capa do livro\"/>\n\t</div>\n</body>\n</html>";
				$cover = fopen("../projetos/$titulo/epub/cover.xhtml", "w");
				fwrite($cover, $covertxt);
				fclose($cover);
			}


			// Criar content.opf
			$soMinha = new SoMinha();
			$soMinha->criarContentOPF($titulo, $idioma, $metadados, $autor, $files, $capa);

			// Criar container.xml
			$containertxt = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<container version=\"1.0\" xmlns=\"urn:oasis:names:tc:opendocument:xmlns:container\">\n\t<rootfiles>\n\t\t<rootfile full-path=\"OEBPS/content.opf\" media-type=\"application/oebps-package+xml\"/>\n\t</rootfiles>\n</container>";
			$container = fopen("../projetos/$titulo/epub/container.xml", "w");
			fwrite($container, $containertxt);
			fclose($container);


			// Criar arquivo epub
			$caminho = "../projetos/$titulo/epub/";
			$arquivos = array_diff(scandir($caminho, 1), ['.', '..']);
			$soMinha = new SoMinha();
			$soMinha->zipEpub($caminho, $titulo, $arquivos);

			// Excluir a pasta epub
			$caminho = "../projetos/$titulo/epub";
			$soMinha = new SoMinha();
			$soMinha->deletarPasta($caminho);

			break;

}


if($VoltarAPaginaAnterior == true){
	if(isset($erro)){
		header("Location: $pagAnterior?erro=$erro");
	}else{
		header("Location: $pagAnterior");
	}
}