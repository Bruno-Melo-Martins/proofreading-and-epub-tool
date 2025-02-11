<?php

	require "modelo.php";
	require "service.php";
	require "conexao.php";


	$acao = isset($_GET['acao']) ? $_GET['acao'] : $acao;

	switch($acao){
		case "criarnovoprojeto":
			// LEMBRAR fazer um verificador de tabela já existente

			// Pegar os valores do formulário
			$titulo = $_POST['titulo'];
			$autor = $_POST['autor'];
			$tipo = $_POST['tipo'];

			if($tipo == 'on'){
				$arquivopdf = $_FILES['pedefe']['tmp_name'];
			}
			$fonte = "";
			
			// Pegar o texto do txt
			$arquivofonte = fopen($_FILES['fonte']['tmp_name'], 'r');

			while (!feof($arquivofonte)) {
				$line = fgets($arquivofonte);
				$line = trim($line);
				$fonte .= $line. PHP_EOL;

			}

			
			fclose($arquivofonte);

			
			// Criar a pasta do projeto e mover arquivo pdf para ela caso exista
			// path será usada para definir caminhos somente aqui
			$path = './projetos/'. $titulo;


			if(!file_exists($path)){
				// Criar pasta do projeto
				$path = '../projetos/'. $titulo;
				mkdir($path, 0777);

				// Criar pasta do arquivo pdf
				$path = '../projetos/'. $titulo. '/arquivopdf/';
				mkdir($path, 0777);

				if($tipo == "on"){
					// Mover arquivo pdf para pasta do projeto
					$path = '../projetos/'. $titulo. '/arquivopdf/'. $titulo. '.pdf';
					move_uploaded_file($arquivopdf, $path);
				}
			}
			
			// Caminho que será registrado na tabela
			$caminho = 'projetos/'. $titulo;

			// Criação do nome (distinto de titulo por não ter espaçamento nem caracteres maiusculos)
			$nome = strtolower(str_replace(' ', '-', $titulo));

			// INSERIR PROJETO NOVO NA TABELA TB_PROJETOS:
				$tarefa = new Tarefa();

				// Definir dados a serem inseridos
				$tarefa->__set('titulo', $titulo);
				$tarefa->__set('nome', $nome);

				// Estabelecer conexão
				$conexao = new Conexao();

				// Iniciar a tarefa
				$tarefaService = new TarefaService($conexao, $tarefa);

				// Executar
				$tarefaService->inserirprojetolista();

			// CRIAR NOVA TABELA COM DADOS DO PROJETO
				$tarefa = new Tarefa();

				// Definir dados a serem inseridos
				$tarefa->__set('titulo', $titulo);
				$tarefa->__set('nome', $nome);
				$tarefa->__set('autor', $autor);
				if($tipo == "on"){
					$tarefa->__set('tipo', 2);
				}else{
					$tarefa->__set('tipo', 1);
				}
				$tarefa->__set('fonte', $fonte);
				

				// Estabelecer conexão
				$conexao = new Conexao();

				// Iniciar a tarefa
				$tarefaService = new TarefaService($conexao, $tarefa);

				// Executar
				$tarefaService->inserirprojetonovo();

				header("Location: ../index.php");
		break;

		case "buscarlista":
			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefaService = new TarefaService($conexao, $tarefa);
			$lista = $tarefaService->buscarlista();
		break;

		case "buscarprojeto":
			$tarefa = new Tarefa();
			$conexao = new Conexao();

			$tarefa->__set('nome', $nome);

			$tarefaService = new TarefaService($conexao, $tarefa);
			$projeto = $tarefaService->buscarprojeto();
		break;
	}
	

	