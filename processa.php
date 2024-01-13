<?php
session_start();
include_once("_app/conf.php");
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);


	if(isset($dados['importar'])):

	if(empty($dados['arquivo'])){
		$_SESSION['msg'] = "<div class='trigger error'>Selecione o arquivo contendo os códigos e depois clique em importar.</div>";
		header("Location: ./");
	}

	$arquivo_tmp = $_FILES['arquivo']['tmp_name'];
	//ler todo o arquivo para um array
	$arquivo = file($arquivo_tmp);


	foreach ($arquivo as $linha) {
		$linha = trim($linha);
		$valor = explode(',', $linha);

		$cod_prod = $valor[0];
		

		$codigo = "INSERT INTO codigo (codigo) VALUE (:codigo)";
		$cod = $pdo->prepare($codigo);
		$cod->bindParam(':codigo', $cod_prod);
		$cod->execute();

	}
	$_SESSION['msg'] = "<div class='trigger accept'>Carregado os dados com sucesso!</div>";
		header("Location: ./");
	elseif(empty($dados['importar'])):
		header("Location: ./");
endif;
