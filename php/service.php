<?php


//CRUD
class TarefaService {

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao, Tarefa $tarefa) {
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}


	public function inserirprojetolista() { //create
		$query = 'insert into tb_lista_projetos(nome, titulo) values(:nome, :titulo)';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':nome', $this->tarefa->__get('nome'));
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->execute();
	}

	public function inserirprojetonovo() { //create
		$query = 'insert into tb_projetos(nome, titulo, autor, tipo, fonte, fonte_bkp) values(:nome, :titulo, :autor, :tipo, :fonte, :fonte)';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':nome', $this->tarefa->__get('nome'));
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->bindValue(':autor', $this->tarefa->__get('autor'));
		$stmt->bindValue(':tipo', $this->tarefa->__get('tipo'));
		$stmt->bindValue(':fonte', $this->tarefa->__get('fonte'));
		$stmt->execute();
	}

	public function buscarlista() { //read
		$query = '
			select 
				nome, titulo, criado
			from 
				tb_lista_projetos
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function buscarprojeto() { //read
		$query = '
			select 
				*
			from 
				tb_projetos
			where
				nome = :nome
		';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':nome', $this->tarefa->__get('nome'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}


}

?>