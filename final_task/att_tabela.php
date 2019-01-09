<?php 
require_once('geraCadastro.php');

if (isset($_POST['json']) and $_POST['json'] != "tabela_inicial") {
	$json = $_POST['json'];

	$json = json_encode($json,true);

	$tabela = tabela_nova($json);


}elseif(isset($_POST['json']) == 'tabela_inicial'){
	echo "tabela antiga";
	$tabela = tabela_nova(geraCadastro());

	

}







