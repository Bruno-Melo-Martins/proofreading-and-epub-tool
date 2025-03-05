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
		$pastas = ["../projetos/$titulo", "../projetos/$titulo/arquivos", "../projetos/$titulo/backup", "../projetos/$titulo/ebook", "../projetos/$titulo/ebook/images"];

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
			$tarefa->__set('metadados', $metadados);
			$tarefaService = new BancoDados($conexao, $tarefa);
			$tarefaService->inserirmetadados();

		break;
		/* CONECTADO COM ETAPA-1.PHP */
		case 'buscartxt':
			$caminho = "./projetos/$titulo/arquivos/$titulo.txt";
			$txt = fopen($caminho, "r");
			$soMinha = new SoMinha();
			$texto = $soMinha->txtparatexto($txt);
			session_start();
			$_SESSION['texto'] = [$titulo, $texto];
			break;
}

if($VoltarAPaginaAnterior == true){
	if(isset($erro)){
		header("Location: $pagAnterior?erro=$erro");
	}else{
		header("Location: $pagAnterior");
	}
}