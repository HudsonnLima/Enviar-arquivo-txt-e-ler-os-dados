<?php
session_start();
include_once("_app/conf.php");
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<!-- Bootstrap CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!--BOOTSTRAP 5.0.2-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<!--//BOOTSTRAP 5.0.2-->
	<!--ICONES-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.1.2/css/fontawesome.min.css">
	<!--//ICONES-->
	<!--JQUERY-UI-->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<!--//JQUERY-UI-->

	<script type="text/javascript" src="js/jquery-3.7.0.min.js"></script>
	<title>Importar TXT</title>
</head>

<body>
	<div class="container">

		<?php
		if (isset($_SESSION['msg'])) {
			echo $_SESSION['msg'];
			unset($_SESSION['msg']);
		}
		?>
		<br />
		<form method="POST" action="processa.php" enctype="multipart/form-data">

			<div class="row g-2">
				<div class="col-md-5">
					<input class="form-control" name="arquivo" type="file">
				</div>
				<div class="col-md-5">
					<input disabled type="text" class="form-control" name="codigo" placeholder="Digite o código">
				</div>
				<div class="col-md-2">
					<input type="submit" class="form-control btn btn-primary" name="importar" value="Importar">
				</div>
			</div>

		</form>
		<br />


		<?php
		$produtos = "SELECT cod.codigo, COUNT(cod.codigo) AS qtd, prod.produto as produto, prod.estoque as estoque
		FROM  codigo AS cod 
		INNER JOIN produtos AS prod 
		ON cod.codigo = prod.codigo
		GROUP BY cod.codigo HAVING COUNT(cod.codigo) >= 1 ORDER BY produto ASC";
		$data = $pdo->prepare($produtos);
		$data->execute();
		$count = $data->rowCount();


		if ($count > 0) {

		?>


			<table class="table">
				<thead>
					<tr>
						<th scope="col">Produto</th>
						<th class="dados" scope="col">Código</th>
						<th class="dados" scope="col">Quantidade Coletada</th>
						<th class="dados" scope="col">Quantidade em estoque</th>
					</tr>
				</thead>


			<?php
		}
			?>
			<tbody>
				<tr>
					<?php
					foreach ($data as $cod) :

						if (!isset($dados['filtrar'])) :
					?>
							<?php if ($cod['estoque'] == $cod['qtd']) { ?>
								<td class="verificado"><?= $cod['produto'] ?></td>
								<td class="dados verificado"><?= $cod['codigo'] ?></td>
								<td class="dados verificado"><?= $cod['qtd'] ?></td>
								<td class="dados verificado"><?= $cod['estoque'] ?></td>
							<?php } elseif ($cod['estoque'] > $cod['qtd']) { ?>
								<td class="negativo"><?= $cod['produto'] ?></td>
								<td class="dados negativo"><?= $cod['codigo'] ?></td>
								<td class="dados negativo"><?= $cod['qtd'] ?></td>
								<td class="dados negativo"><?= $cod['estoque'] ?></td>
							<?php } elseif ($cod['estoque'] < $cod['qtd']) { ?>
								<td class="positivo"><?= $cod['produto'] ?></td>
								<td class="dados positivo"><?= $cod['codigo'] ?></td>
								<td class="dados positivo"><?= $cod['qtd'] ?></td>
								<td class="dados positivo"><?= $cod['estoque'] ?></td>
						<?php }
						endif; ?>


						<?php

						if (isset($dados['filtrar'])) :
							if ($dados['filtro'] == 0) { ?>
								<?php if ($cod['estoque'] == $cod['qtd']) { ?>
									<td class="verificado"><?= $cod['produto'] ?></td>
									<td class="dados verificado"><?= $cod['codigo'] ?></td>
									<td class="dados verificado"><?= $cod['qtd'] ?></td>
									<td class="dados verificado"><?= $cod['estoque'] ?></td>
								<?php } elseif ($cod['estoque'] < $cod['qtd']) { ?>
									<td class="positivo"><?= $cod['produto'] ?></td>
									<td class="dados positivo"><?= $cod['codigo'] ?></td>
									<td class="dados positivo"><?= $cod['qtd'] ?></td>
									<td class="dados positivo"><?= $cod['estoque'] ?></td>
								<?php } elseif ($cod['estoque'] > $cod['qtd']) { ?>
									<td class="negativo"><?= $cod['produto'] ?></td>
									<td class="dados negativo"><?= $cod['codigo'] ?></td>
									<td class="dados negativo"><?= $cod['qtd'] ?></td>
									<td class="dados negativo"><?= $cod['estoque'] ?></td>
								<?php } ?>
							<?php
							} elseif ($dados['filtro'] == 1 and $cod['estoque'] < $cod['qtd']) {
							?>
								<td class="positivo"><?= $cod['produto'] ?></td>
								<td class="dados positivo"><?= $cod['codigo'] ?></td>
								<td class="dados positivo"><?= $cod['qtd'] ?></td>
								<td class="dados positivo"><?= $cod['estoque'] ?></td>
							<?php
							} elseif ($dados['filtro'] == 2 and $cod['estoque'] > $cod['qtd']) {
							?>
								<td class="negativo"><?= $cod['produto'] ?></td>
								<td class="dados negativo"><?= $cod['codigo'] ?></td>
								<td class="dados negativo"><?= $cod['qtd'] ?></td>
								<td class="dados negativo"><?= $cod['estoque'] ?></td>
							<?php

							} elseif ($dados['filtro'] == 3 and $cod['estoque'] == $cod['qtd']) {
							?>
								<td class="verificado"><?= $cod['produto'] ?></td>
								<td class="dados verificado"><?= $cod['codigo'] ?></td>
								<td class="dados verificado"><?= $cod['qtd'] ?></td>
								<td class="dados verificado"><?= $cod['estoque'] ?></td>
						<?php }
						endif; ?>
				</tr>
			</tbody>
		<?php endforeach; ?>
			</table>
			<?php 
			$data = "SELECT * FROM codigos";
			$data = $pdo->prepare($produtos);
			$data->execute();
			$count = $data->rowCount();
			if ($count != 0) { ?>

				<form method="POST" action="actions/deletar_dados.php">
					<div class="row g-2">
						<div class="col-auto">
							<input type="submit" name="deletar" class="btn btn-danger mb-3" value="Excluir dados">
						</div>
					</div>
				</form>
			<?php } ?>

			<br /><br /><br /><br />

	</div>

</body>



</html>