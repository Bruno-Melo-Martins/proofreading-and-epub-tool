<?php

	require "modelo.php";
	require "service.php";
	require "conexao.php";


	$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

	switch($acao){
		case "criarnovoprojeto":
			// VERIFICAR ARQUIVOS:
				$files[] = $_FILES['fonte']['tmp_name'];
				// Definir tipo (1 ou 2)
				if(isset($_POST['tipo'])){
					$tipo = 2;
					$files[] = $_FILES['pedefe']['tmp_name'];
				}else{
					$tipo = 1;
				}

				$soMinha = new SoMinha();
				$resultado = $soMinha->verificarelementos($files);
				if($resultado == 'Erro: Arquivo faltando'){
					//echo 'faiô';
					header("Location: ../index.php?erro=$resultado");
					break;
				}else{
					//echo 'acertô';
				}
			// DECLARAR VARIÁVEIS
				$titulo = $_POST['titulo'];
				$autor = $_POST['autor'];
				$fonte = $_FILES['fonte']['tmp_name'];
				if($tipo == 2){
					$pdf = $_FILES['pedefe']['tmp_name'];
				}

			// VERIFICAR SE JÁ EXISTE UM PROJETO DE MESMO NOME EM TB_PROJETOS:

				$tarefa = new Tarefa();
				$conexao = new Conexao();

				$tarefa->__set('titulo', $titulo);
				//echo "$titulo <br>";

				$tarefaService = new TarefaService($conexao, $tarefa);
				$resultado = $tarefaService->verificartabela();

				if ($resultado[0]['COUNT(*)'] != 0){
					header('Location: ../index.php?erro=ProjetoJaExiste');
					break;
				}
				
			// CRIAR PASTA DO PROJETO E SUBPASTAS

			$caminho = "../projetos/$titulo";


			if(!file_exists($caminho)){
				// Criar pasta do projeto
				mkdir($caminho, 0777);
				// Criar pasta dos arquivos e backup
				$caminho = "../projetos/$titulo/arquivos/";
				mkdir($caminho, 0777);
				$caminho = "../projetos/$titulo/backup/";
				mkdir($caminho, 0777);

				// Mover arquivo txt para pasta do projeto
				$caminho = "../projetos/$titulo/arquivos/$titulo.txt";
				move_uploaded_file($fonte, $caminho);

				// Mover arquivo txt para pasta do backup
				$caminho = "../projetos/$titulo/backup/$titulo.txt";
				move_uploaded_file($fonte, $caminho);

				// Se é tipo dois também coloque o pdf
				if($tipo == 2){
					// Mover arquivo pdf para pasta do projeto
					$caminho = "../projetos/$titulo/arquivos/$titulo.pdf";
					move_uploaded_file($pdf, $caminho);
				}
				// Criar um backup
				$caminho = "../projetos/$titulo/arquivos/$titulo.txt";
				move_uploaded_file($fonte, $caminho);
			}
			// INSERIR DADOS NA TABELA:

			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('titulo', $titulo);
			$tarefa->__set('autor', $autor);
			$tarefa->__set('tipo', $tipo);

			//echo "$titulo <br>";

			$tarefaService = new TarefaService($conexao, $tarefa);
			$tarefaService->inserirprojetonovo();

			header("Location: ../index.php");
		break;

		case 'buscarlista':
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefaService = new TarefaService($conexao, $tarefa);
			$lista = $tarefaService->buscarlista();
		break;

		case 'excluirprojeto':
			// CONSEGUIR O TITULO
				$titulo = $_POST['titulo'];
				echo $titulo;

			// EXCLUIR PASTA E SUBPASTAS
			$caminho = "../projetos/$titulo";
			$soMinha = new SoMinha();

			if ($soMinha->deletarPasta($caminho)) {
				echo "Pasta excluída com sucesso.";
			} else {
				echo "Erro ao excluir a pasta.";
			}

			// EXCLUIR LINHA DA TABELA

			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('titulo', $titulo);

			$tarefaService = new TarefaService($conexao, $tarefa);
			$tarefaService->excluirprojeto();

			header("Location: ../index.php");


		break;

		case 'buscarprojeto':
			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('titulo', $titulo);

			$tarefaService = new TarefaService($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto();
		break;

		case 'buscartxtbruto':
			$caminho = "projetos/$titulo/arquivos/$titulo.txt";
			$tocaminho = "projetos/$titulo/arquivos/toc.xhtml";

            $arquivo = fopen($caminho, 'r');

			$soMinha = new SoMinha();
			$texto = $soMinha->txtparatexto($arquivo);

			if(file_exists($tocaminho)){
				$arquivo = fopen($tocaminho, 'r');
				$soMinha = new SoMinha();
				$toc = $soMinha->txtparatexto($arquivo);
			}
			
			
		break;

		case 'salvartxtbruto':
			$texto = $_POST['editor'];
			$titulo = $_POST['titulo'];

			$txtcaminho = "../projetos/$titulo/arquivos/$titulo.txt";
			$tocaminho = "../projetos/$titulo/arquivos/toc.xhtml";

			$txtvetor = explode("\n", $texto);

			// PRODUZIR UM TOC:
			$titles = array('<(H1)>', '<(H2)>', '<(H3)>');
			$toc = '<ol>'. PHP_EOL;

			foreach($txtvetor as $linha){
				foreach($titles as $title){
					if(str_contains($linha, $title)){
						$linetoc = trim(str_replace($title, '', $linha));
						$toc .= "<li>$linetoc</li>". PHP_EOL;
						
					}
				}
			}

			$toc .= '</ol>';
			//echo $toc;

			// SALVAR ARQUIVO TOC
			$tocfile = fopen($tocaminho, 'w');
			fwrite($tocfile, $toc);
			fclose($tocfile);

			// SOBRESCREVER ARQUIVO TXT:
			file_put_contents($txtcaminho, $texto);

			// VOLTAR PAR O EDITOR:
			header("Location: ../etapa1.php?titulo=$titulo");



		break;

		case 'subiretapa':
			if(isset($_GET['titulo'])){
				$titulo = $_GET['titulo'];
			}
			//echo $titulo;

			// CRIAR PASTA EBOOK
			$pasta = "../projetos/$titulo/ebook";
			if(!file_exists($pasta)){
				mkdir($pasta, 0777);
			}
			

			// CRIAR ARQUIVOS XHTML COM BASE NOS TÍTULOS
			$soMinha = new SoMinha();
			$soMinha->dividirxhtml($titulo);

			// CRIAR ARQUIVOS XHTML COM BASE NOS TÍTULOS
			$soMinha = new SoMinha();
			$soMinha->criartoc($titulo);

			// CRIAR STYLE CSS COM BASE NOS TÍTULOS
			$soMinha = new SoMinha();
			$soMinha->criarstyle($titulo);


			// MUDAR CAMPO ETAPA EM TB_PROJETOS

			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('titulo', $titulo);

			$tarefaService = new TarefaService($conexao, $tarefa);
			$tarefaService->subirEtapa2();
			header("Location: ../acessarprojeto.php?titulo=$titulo");

		break;

		case 'buscarhtml':
			$arquivo = fopen("./$htmlpath", 'r');

			$soMinha = new SoMinha();
			$htmltxt = $soMinha->txtparatexto($arquivo);

			$css = fopen("./projetos/$titulo/ebook/style.css", "r");

			$soMinha = new SoMinha();
			$csstxt = $soMinha->txtparatexto($css);

		break;

		case 'salvarhtml':
			$link = "../$_GET[link]";
			$texto = $_POST['textohtml'];
			$textocss = $_POST['editorcss'];
			$titulo = $_GET['titulo'];
			$css = "../projetos/$titulo/ebook/style.css";

			echo "$texto <br> $textocss";
			file_put_contents($link, $texto);
			file_put_contents($css, $textocss);

			header("Location: ../acessarprojeto.php?titulo=$titulo");

		break;
	}	