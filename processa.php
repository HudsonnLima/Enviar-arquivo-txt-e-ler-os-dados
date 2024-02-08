<?php
session_start();
include_once("_app/conf.php");
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


if (isset($dados['importar'])) :
	

	if (isset($_FILES['arquivo']) and ($_FILES['arquivo']['type'] == 'text/plain')) {
		$arquivo_tmp = $_FILES['arquivo']['tmp_name'];
		//ler todo o arquivo para um array
		$arquivo = file_get_contents($arquivo_tmp);
		$lista_array = explode("\r\n", $arquivo);
		$contagem = array_count_values($lista_array);

		foreach ($contagem as $codigo => $quantidade) {

			echo "$codigo - $quantidade" . '<br/>';
			$data = "INSERT INTO codigo (codigo, quantidade) VALUE (:codigo, :quantidade)";
			$cod = $pdo->prepare($data);
			$cod->bindParam(':codigo', $codigo);
			$cod->bindParam(':quantidade', $quantidade);
			$cod->execute();

			$_SESSION['msg'] = "<div class='trigger accept'>Arquivo importado com sucesso!</div>";
			header("Location: ./");
		}

	} elseif (!empty($dados['codigo'])) {


		//CADASTRA O CÓDIGO DIGITADO
		$codigo = "INSERT INTO codigo (codigo) VALUE (:codigo)";
		$cod = $pdo->prepare($codigo);
		$cod->bindParam(':codigo', $dados['codigo']);
		$cod->execute();
		$count = $cod->rowCount();
		$_SESSION['msg'] = "<div class='trigger accept'>Código importado com sucesso!</div>";
		header("Location: ./");
	} else {
		$_SESSION['msg'] = "<div class='trigger error'>Campo em branco ou formato do arquivo inválido.<br/> Permitido apenas arquivos ( .txt )</div>";
		header("Location: ./");
	};
endif;

