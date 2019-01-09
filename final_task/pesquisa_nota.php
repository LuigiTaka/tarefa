<?php 
ob_start();
$match = [];
$json = $_POST['json'];
$nome = $_POST['nome'];
$passou = $POST['passou'];


if (empty($_POST['nome'])) {
	echo "Nome colocado inválido, digite novamente.";
}

for ($i=0; $i < count($json) ; $i++) { 
	if ($nome == $json[$i]['nome']) {
		for ($m=0; $m < count($json[$i]['materias']) ; $m++) { 
			$match[] = ["materia" => $json[$i]['materias'],"nota" => $json[$i]['notas'][$m]];
			if (array_sum($json[$i]['materias']) > 6) {
				$passou = true;
				$match[] = $passou;
			}
		}
	}
}


$match = json_encode($match,true);
ob_end_clean();

echo $match;
