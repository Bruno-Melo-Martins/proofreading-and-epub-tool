<?php

require "modelo.php";
require "service.php";
require "conexao.php";

// Receber as principais strings do projeto
if(isset($_GET['acao'])){
	$acao = $_GET['acao'];
}

if(isset($_POST['titulo'])){
	$titulo = $_POST['titulo'];
}

$pagAnterior = $_SERVER['HTTP_REFERER'];

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
		$elementos = ['titulo','autor', 'idioma', 'forma'];
		$arquivos[] = 'txt';


		// Declarar tipo:
		if(isset($_POST['tipo'])){
			$tipo = 2;
			$pdf = $_FILES['pdf']['tmp_name'];
			$arquivos[] = 'pdf';
		}else{
			$tipo = 1;
		}

		// Verificar elementos
		$soMinha = new SoMinha();
		$tudocerto = $soMinha->verificarElementos($elementos, $arquivos);
		if($tudocerto == false){
			break;
		}

		// Verificar se há títulos iguais em tb_projetos:
			$tarefa = new Tarefa();
			$conexao = new Conexao();
			$tarefa->__set("titulo", $titulo);
			$bancoDados = new BancoDados($conexao, $tarefa);
			$numeroLinhas = $bancoDados->verificarTitulos();
			//print_r($numeroLinhas[0]['COUNT(*)']);
			if($numeroLinhas[0]['COUNT(*)'] != 0){
				break;
			}
		
		// Criar pastas do projeto:
		$pastas = ["../projetos/$titulo", "../projetos/$titulo/arquivos", "../projetos/$titulo/backup"];

		$soMinha = new SoMinha();
		$soMinha->criarPastas($pastas);

		$pastas = [];

		// Mover arquivos para pastas
		move_uploaded_file($txt, "../projetos/$titulo/arquivos/$titulo.txt");
		copy("../projetos/$titulo/arquivos/$titulo.txt", "../projetos/$titulo/backup/$titulo.txt");
		if($tipo == 2){
		move_uploaded_file($pdf, "../projetos/$titulo/arquivos/$titulo.pdf");
		}

		// Inserir projeto em tb_projetos
		$tarefa = new Tarefa();
		$conexao = new Conexao();
		$tarefa->__set("titulo", $titulo);
		$tarefa->__set("autor", $autor);
		$tarefa->__set("idioma", $idioma);
		$tarefa->__set("forma", $forma);
		$tarefa->__set("tipo", $tipo);
		$bancoDados = new BancoDados($conexao, $tarefa);
		$bancoDados->inserirprojeto();
		break;

		case 'buscarlista':
			$VoltarAPaginaAnterior = false;
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
}

if($VoltarAPaginaAnterior == true){
	header("Location: $pagAnterior");
}