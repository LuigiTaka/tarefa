<?php 
require_once(__DIR__.'/../utils.php');
//Os três primeiros autores ficam com {id_autor: null}, não consegui resolver essa parte :/ e também não rolou fazer tudo dentro de um if só.
$file = file_get_contents("http://htput.com/luigi_project/obras?contentType=text/plain"); // Pega o conteudo do link
#texto = file_get_contents(");	

$array = explode("\r", $file); // divide por quebra de linha
for ($i=0; $i < count($array) ; $i++) { 

	$array[$i] = explode(';', trim($array[$i]));

	if(!$a = getRegistro('autores','nome',$array[$i][1])){
	    $a = ['nome' => $array[$i][1]];
	    $a['id'] = addRegistro('autores',$a);
    }
   

    if(!getRegistro('obras','obra',$array[$i][0])){
    	$texto = file_get_contents("http://htput.com/luigi_project/obra-".$array[$i][2]);
    	$f = addLargeText($texto);
        addRegistro('obras',['obra'=> $array[$i][0], 'id_autor' => $a['id'],"id_obra" =>$array[$i][2] ,'id_large_text' => $f]);
        // Inclui no array também os ids das obras, não era necessário mas achei melhor pra ficar mais completinho.
    }    
}
