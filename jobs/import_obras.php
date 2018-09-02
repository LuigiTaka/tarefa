<?php 
require_once(__DIR__.'\..\utils.php');

$file = file_get_contents("http://htput.com/luigi_project/obras?contentType=text/plain"); // Pega o conteudo do link

$array = explode("\n", $file); // divide por quebra de linha

for ($i=0; $i < count($array) ; $i++) {   
	$array[$i] = explode(';', $array[$i]); // divide cada $array por ;
	if (is_array($array[$i])) {
		if (!getRegistro('autores','nome',$array[$i][1])) { // se o registro não existir efetua o registro
			addRegistro('autores',['nome' => $array[$i][1]]);
			echo "Registro efetuado<br>";
		}elseif ($a = getRegistro('autores','nome',$array[$i][1])) { //se o registro existir efetua o registro em obras.
			if (!getRegistro('obras', 'obra', $array[$i][0])) { // se não existir registros no arquivo obras, registra! OBS: da erro na primeira vez por que o arquivo ainda não foi criado :v
					addRegistro('obras',['id_autor' => $a['id'], 'obra' => $array[$i][0]]); 
			}
		}
	}
}


