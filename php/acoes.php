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

			// VERIFICAR SE JÁ EXISTE UM PROJETO DE MESMO NOME EM TB_LISTA_PROJETOS:

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
				// Criar pasta do arquivo pdf
				$caminho = "../projetos/$titulo/arquivos/";
				mkdir($caminho, 0777);
				// Mover arquivo txt para pasta do projeto
				$caminho = "../projetos/$titulo/arquivos/$titulo.txt";
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
			// INSERIR DADOS NAS TABELAS:

			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('titulo', $titulo);
			$tarefa->__set('autor', $autor);
			$tarefa->__set('tipo', $tipo);

			//echo "$titulo <br>";

			$tarefaService = new TarefaService($conexao, $tarefa);
			$tarefaService->inserirprojetolista();
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

			// EXCLUIR LINHAS DAS TABELAS

			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('titulo', $titulo);

			$tarefaService = new TarefaService($conexao, $tarefa);
			$tarefaService->excluirprojeto();
			$tarefaService->excluirprojetolista();

			header("Location: ../index.php");


		break;
	
	}

	