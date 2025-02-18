<?php


//CRUD
class TarefaService {

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao, Tarefa $tarefa) {
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}


	public function verificartabela(){
		$query = 
				"SELECT COUNT(*) 
				FROM tb_projetos 
				WHERE titulo = :titulo ";

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
		//return $stmt->fetchAll(PDO::FETCH_OBJ);
	}

	public function inserirprojetonovo() { //create
		$query = 'INSERT INTO tb_projetos(titulo, autor, tipo) 
		VALUES(:titulo, :autor, :tipo)';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->bindValue(':autor', $this->tarefa->__get('autor'));
		$stmt->bindValue(':tipo', $this->tarefa->__get('tipo'));
		$stmt->execute();
	}

	public function buscarlista() { //read
		$query = 'SELECT * FROM tb_projetos';
		$stmt = $this->conexao->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function buscarprojeto() { //read
		$query = 'SELECT * FROM tb_projetos WHERE titulo = :titulo';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}


	public function excluirprojeto() { //read
		$query = 'DELETE FROM tb_projetos WHERE titulo = :titulo';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->execute();
	}



}

class SoMinha {
	public function verificarelementos($files){
		// Verificar os POST is FILE kjaljfgl
		if(count($files) != 0){
			foreach($files as $file){
				if(isset($file)){
					$resultado = "Os arquivos existem";
					
				}else{
					echo 'não é arquivo <br>';
					
					return 'Erro: Arquivo faltando';
				}
				return $resultado;
			}
		}
	}

	public function txtparatexto($arquivo){
		// Declarar string
		$fonte = '';
		while (!feof($arquivo)) {
			$line = fgets($arquivo);
			$line = trim($line);
			$fonte .= $line. PHP_EOL;

		}
		return $fonte;
	}

	public function txtparavetor($arquivo){

		while (!feof($arquivo)) {
			$line = fgets($arquivo);
			$line = trim($line);
			$vetor[] = $line;

		}
		return $vetor;
	}

	public function criartoc($arquivo){
		$toc = '<nav epub:type="toc" role="doc-toc" aria-label="Table of Contents"><ol>';
		$titles = array('<(H1)>', '<(H2)>', '<(H3)>');
		while (!feof($arquivo)) {
			$line = fgets($arquivo);
			$line = trim($line);
			$x=1;
			
			$toc .= "<li class='$title' id='np$x'>$line</li>";
			foreach($titles as $title){
				if(str_ends_with($line, $title)){
					$line = trim(str_replace($title, '', $line));
					$toc .= "<li class='$title' id='np$x'>$line</li>";
					$x++;
				}
			}

			
		}
		
		$toc .= '</ol></nav>';
		return $toc;
	}


	public function deletarPasta($folderPath) {
		if (!is_dir($folderPath)) {
			echo "A pasta não existe.";
			return false;
		}
		
		$files = array_diff(scandir($folderPath), array('.', '..'));
		
		foreach ($files as $file) {
			$filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
			
			if (is_dir($filePath)) {
				$this->deletarPasta($filePath); // Recursivamente deleta subpastas
			} else {
				unlink($filePath); // Deleta arquivos
			}
		}
		
		return rmdir($folderPath); // Deleta a pasta vazia
	}

	public function conversordeData($data){
		$ingles = array('of', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$portugues = array('de', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
		$data = str_replace($ingles, $portugues, $data);
		return $data;
	}

}

?>