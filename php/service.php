<?php


//CRUD
class BancoDados {

	private $conexao;
	private $tarefa;

	public function __construct(Conexao $conexao, Tarefa $tarefa) {
		$this->conexao = $conexao->conectar();
		$this->tarefa = $tarefa;
	}


	public function verificarTitulos(){
		$query = "SELECT COUNT(*) FROM tb_projetos WHERE titulo = :titulo ";

		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function inserirprojeto() { //create
		$query = 'INSERT INTO tb_projetos (titulo, autor, tipo, idioma, forma, metadados) VALUES(:titulo, :autor, :tipo, :idioma, :forma, :metadados)';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->bindValue(':autor', $this->tarefa->__get('autor'));
		$stmt->bindValue(':tipo', $this->tarefa->__get('tipo'));
		$stmt->bindValue(':idioma', $this->tarefa->__get('idioma'));
		$stmt->bindValue(':forma', $this->tarefa->__get('forma'));
		$stmt->bindValue(':metadados', $this->tarefa->__get('metadados'));
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

	public function subiretapa() { //read
		$query = 'UPDATE tb_projetos SET etapa=2 WHERE titulo = :titulo';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->execute();
	}

	public function inserirMeta(){
		$query = 'UPDATE tb_projetos SET metadados=:valor WHERE titulo = :titulo';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->bindValue(':valor', $this->tarefa->__get('valor'));
		$stmt->execute();
	}

	public function inserirToc(){
		$query = 'UPDATE tb_projetos SET toc=:valor WHERE titulo = :titulo';
		$stmt = $this->conexao->prepare($query);
		$stmt->bindValue(':titulo', $this->tarefa->__get('titulo'));
		$stmt->bindValue(':valor', $this->tarefa->__get('valor'));
		$stmt->execute();
	}



}

class SoMinha {

	public function criarPastas($pastas){
		foreach($pastas as $pasta){
			if(!file_exists($pasta)){
				mkdir($pasta, 0777);
			}
		}
	}

	/*public function verificarElementos($elementos, $files){ NÃO FUNCIONOU
		foreach($elementos as $elemento){
			if(isset($_POST[$elemento]) && $_POST[$elemento] != ''){
				$existe = true;
			}else{
				return false;
			}
		}
		if(count($files) != 0){
			foreach($files as $file){
				if(!is_file("$_FILES[$file][tmp_name]")){
					return false;
				}else{
					if(!str_contains("$_FILES[$file][tmp_name]", ".$file")){
						return false;
					}
				}
			}
		}
		return $existe;
	}*/

	public function txtparatexto($arquivo){
		// Declarar string
		$fonte = '';
		while (!feof($arquivo)) {
			$line = fgets($arquivo);
			$line = trim($line);
			$fonte .= $line. PHP_EOL;
		}
		fclose($arquivo);
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

	public function tocParaLista($titles, $links){
		// z é o Numero de </ol> finais para adicionar, 1 por default
		$z = 1; // Contador de ol abertos
		$lista = "<ol>\n"; 
		$ultimo = 0; 
		$y = 0;

		foreach ($titles as $vetor) {
			$h = (int) $vetor['H']; 
			$title = $vetor['C'];

			if ($ultimo == 0) {
				$ultimo = $h;
			} elseif ($h > $ultimo) { 
				// Abrir novo nível de lista
				// Para obedecer o XHTML restrito
				if($links){
					$lista = substr($lista, 0, -6);
					$lista .= "\n<ol>\n"; 
				}else{
					$lista .= "<ol>\n"; 
				}
				$z++;
			} elseif ($h < $ultimo) { 
				// Fechar listas até alcançar o nível correto
				while ($h < $ultimo) {
					if($links){
						$lista .= "</ol></li>\n"; 
					}else{
						$lista .= "</ol>\n";
					}	
					$z--;
					$ultimo--;
				}
			}

			// Adicionar item da lista
			if ($links) {
				$lista .= "<li><a href=\"text_ebook_$y.xhtml\">$title</a></li>\n";
				$y++;
			} else {
				$lista .= "<li>$title</li>\n";
			}

			$ultimo = $h;
		}

		// Fechar quaisquer listas abertas no final
		while ($z > 1) {
			if($links){
				$lista .= "</ol></li>\n";
			}else{
				$lista .= "</ol>\n";
			}
			
			$z--;
		}

		$lista .= "</ol>\n";
		return $lista;
	}

	/*public function conversordeData($data){
		$ingles = ['of', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
		$portugues = ['de', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
		$data = str_replace($ingles, $portugues, $data);
		return $data;
	}*/

	public function dividirxhtml($titulo, $forma, $idioma){
		$txtpath = "../projetos/$titulo/arquivos/$titulo.txt";
		$txt = fopen($txtpath, 'r');
		$linhas = $this->txtparavetor($txt);

		$x = 0;
		// Para o id de cada tag título
		$z = 0;

		foreach($linhas as $linha){
			for($y = 1; $y <= 6; $y++){
				if(str_contains($linha, "<h$y>")){
					// Colocar class nos títulos
					$linha = str_replace("<h$y>", "<h$y class=\"titulo titulo$y\" id=\"ti$z\">", $linha);
					$z++;
					$linha = trim($linha);
					$linhas[$x] = $linha;

					// Conseguir o título separado
					$paciencia = strpos($linha, ">") + 1;
					$virtude = substr($linha, $paciencia);
					$capitulos[] = str_replace("</h$y>", "", $virtude);
				}
			}
			$x++;
		}
		
		// Conforme a forma
		switch($forma){
			case 'prosa':
				$paragrafo = '';
				$pontuacao = ['.', '!', '?'];
				$x = 0;
				foreach($linhas as $linha){
					// Se a linha tiver um titulo então ele não será envelopado como parágrafo
					if(!str_starts_with($linha, '<h')){
						if($paragrafo == ''){
							$paragrafo = "<p class=\"paragrafo\">$linha";
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
				break;
				case 'poesia':
					$x = 0;
					foreach($linhas as $linha){
						if(!str_starts_with($linha, '<h') && $linha != ''){
							$final[$x] = "<p class=\"verso\">$linha</p>";
						}else{
							// Se for título adicione-o normalmente
							$final[$x] = $linha;
						}
						$x++;
					}
					break;
		}

		/*foreach($final as $f){
			echo $f;
		}*/
		
		// AGORA FINALMENTE IREMOS ESCREVER NOS ARQUIVOS
		$ebpath = "../projetos/$titulo/ebook";
		$x = 0;
		// Header e final de todos os arquivos em xhtml
		$header = "<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"$idioma\" lang=\"$idioma\">\n<head>\n    <meta charset=\"UTF-8\" />\n    <meta name=\"dc:title\" content=\"$titulo\" />\n    <title>placeholder</title>\n	<link rel=\"stylesheet\" href=\"style.css\" type=\"text/css\" />\n</head>\n<body>\n";

		$end = "\n</body>\n</html>";
		// PRIMEIRO HTML
		$ebooktxt = fopen("$ebpath/text_ebook_$x.xhtml", 'w');
		$head = str_replace("placeholder", $capitulos[$x], $header);
		fwrite($ebooktxt, $head);
		foreach($final as $f){
			if(str_starts_with($f, '<h2')){

				$x++;
				fwrite($ebooktxt, $end);
				fclose($ebooktxt);	
				// DEMAIS HTML
				$ebooktxt = fopen("$ebpath/text_ebook_$x.xhtml", 'w');
				$head = str_replace("placeholder", $capitulos[$x], $header);
				fwrite($ebooktxt, $head);
			}
			fwrite($ebooktxt, $f.PHP_EOL);
		}

		fclose($ebooktxt);
		
		
	}

	public function criartoc($titulo, $toc){
		// Criar lista como o toc
		if($toc != ''){
			$titles = unserialize($toc);
			$links = true;
			$lista = $this->tocParaLista($titles, $links);

			$header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!DOCTYPE html>\n<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:epub=\"http://www.idpf.org/2007/ops\" >\n  <head><title>Índice</title></head>\n  <body>\n    <nav epub:type=\"toc\">\n";
			$end = "	</nav>\n  </body>\n</html>";

			$navtexto = "$header$lista$end";

			$caminho = "../projetos/$titulo/ebook/nav.xhtml";
			$nav = fopen($caminho, "w");
			fwrite($nav, $navtexto);
			fclose($nav);
		}

	}

}

?>