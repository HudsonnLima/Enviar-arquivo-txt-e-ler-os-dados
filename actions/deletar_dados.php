<?php
require_once "../_app/conf.php";
session_start();
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if(isset($dados['deletar'])){
        
    $est_conf = "DELETE FROM codigo";
    $conf = $pdo->prepare($est_conf);
    $conf->execute();

        $_SESSION['msg'] = "<div class='trigger accept'>Dados Excluídos!</div>";
        header("Location: ../");
    }else{
        header("Location: ../");
    }
?>