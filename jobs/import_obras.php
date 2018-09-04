<?php 
require_once(__DIR__.'\..\utils.php');
//Os três primeiros autores ficam com {id_autor: null}, não consegui resolver essa parte :/ e também não rolou fazer tudo dentro de um if só.
$file = file_get_contents("http://htput.com/luigi_project/obras?contentType=text/plain"); // Pega o conteudo do link

$array = explode("\n", $file); // divide por quebra de linha
for ($i=0; $i < count($array) ; $i++) { 
	$array[$i] = explode(';', $array[$i]);
	$b = getRegistro('autores','nome',$array[$i][1]);
	
	if (!$a = getRegistro('autores','nome',$array[$i][1])) {
		$s = addRegistro('autores',['nome' => $array[$i][1]]);
		$f = addRegistro('obras',['obra' => $array[$i][0],'id_autor' => $b['id']]);
		
	}elseif (!getRegistro('obras','id_autor',$array[$i][0])) {
		getRegistro('obras','obra',$array[$i][0]);
		addRegistro('obras',['obra'=> $array[$i][0], 'id_autor' => $b['id']]);
	}
	

}

