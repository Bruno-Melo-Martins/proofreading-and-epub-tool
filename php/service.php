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

	public function subirEtapa2() { //read
		$query = 'UPDATE tb_projetos SET etapa=2 WHERE titulo = :titulo';
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

	public function dividirxhtml($titulo){
		$txtpath = "../projetos/$titulo/arquivos/$titulo.txt";
		$txt = fopen($txtpath, 'r');
		$linhas = $this->txtparavetor($txt);

		$x=0;
		$titles = array('<(H1)>', '<(H2)>', '<(H3)>');

		foreach($linhas as $linha){
			foreach($titles as $title){
				if(str_contains($linha, $title)){
					$linha = str_replace($title, '', $linha);
					$linha = trim($linha);
					switch($title){
						case '<(H1)>':
							$linhas[$x] = "<h1 class='ti-1'>$linha</h1>";
							break;
						case '<(H2)>':
							$linhas[$x] = "<h2 class='ti-2'>$linha</h2>";
							break;
						case '<(H3)>':
							$linhas[$x] = "<h3 class='ti-3'>$linha</h3>";
							break;
					}
				}
			}
			$x++;
		}
			
		$paragrafo = '';
		$pontuacao = array('.', '!', '?');
		$x = 0;
		foreach($linhas as $linha){
			// Se a linha tiver um titulo então ele não será envelopado como parágrafo
			if(!str_starts_with($linha, '<h')){
				if($paragrafo == ''){
					$paragrafo = "<p class='paragrafo'>$linha";
					$final[$x] = $paragrafo;
				}else{
					$paragrafo = " $linha";
					$final[$x] .= $paragrafo;
				}
				foreach($pontuacao as $ponto){
					if(str_ends_with($paragrafo, $ponto)){
						$final[$x] .= '</p>';
						$x++;
						$paragrafo = '';
					}
				}
			}else{
				// Porém, se a linha atual é um título, e o paragrafo anterior 'não nulo' não foi fechado ainda, agora ele será fechado
				//echo $linha;
				if($paragrafo != ''){
					$final[$x] .= '</p>';
					$x++;
					$paragrafo = '';
				}
				
				$final[$x] = $linha;
				$x++;
			}
			
		}

		// AGORA FINALMENTE IREMOS ESCREVER NOS
		$ebpath = "../projetos/$titulo/ebook";
		$x = 0;
		// PRIMEIRO HTML
		$ebooktxt = fopen("$ebpath/text_ebook_$x.xhtml", 'w');
		foreach($final as $f){
			if(str_starts_with($f, '<h2')){
				$x++;
				fclose($ebooktxt);	
				// DEMAIS HTML
				$ebooktxt = fopen("$ebpath/text_ebook_$x.xhtml", 'w');
			}
			fwrite($ebooktxt, $f.PHP_EOL);
		}
		fclose($ebooktxt);


		$tocpath = "../projetos/$titulo/arquivos/toc.xhtml";
		$toc = fopen($tocpath, 'r');
		$tabela = $this->txtparavetor($toc);
		$texto = '';
		foreach($tabela as $linha){
			$texto .= $linha; 
		}

		$texto = str_replace('<ol><li>', '', $texto);
		$texto = str_replace('</li></ol>', '', $texto);

		$linhas = explode('</li><li>', $texto);

		//print_r($linhas);

		$toc = '<?xml version="1.0" encoding="UTF-8"?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="en">
  <head>
    <title>'.$titulo.'</title>
  </head>
  <body epub:type="frontmatter">
  <nav epub:type="toc" role="doc-toc" aria-label="Table of Contents">
      <ol>';
	  	
	 	$x = 0;
		foreach($linhas as $linha){
			$toc .= "<li><a href='text_ebook_$x.xhtml'>$linha</a></li>".PHP_EOL;
			$x++;
		}
		$toc .= '
      </ol>
    </nav>
  </body>
</html>';
		//echo $toc;

		// CRIAR ARQUIVO TOC NA PASTA EBOOK
		$tocpath = "../projetos/$titulo/ebook/toc.xhtml";
		$arquivotoc = fopen($tocpath, 'w');
		fwrite($arquivotoc, $toc);
		fclose($arquivotoc);
		
	}

}

?>