<?php
function geraNome(){
	$name = explode("\n", file_get_contents('names.male.txt'));
	$last = explode("\n", file_get_contents('names.last.txt'));

	/* Shuffle the name arrays, or you'll get the same results every time */

	shuffle($name);
	shuffle($last);

	/* Now dump out ten names, alternating male and female */
	$complete = $name[mt_rand(0,count($name)-1)]." ".$last[mt_rand(0,count($last)-1)];
	return $complete;

}


function geraDatas($inicio = "2018-02-15", $fim = "2018-12-20"){

	$inicio = new DateTime($inicio);
	$fim = new DateTime($fim);

	$periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);
	$validos = [];
	foreach($periodo as $item){

	    if(substr($item->format("D"), 0, 1) != 'S'){
	        $validos[] = $item->format('d/m');
	    }
	}


	$teste = json_encode($validos);

	return $teste;
}

function geraCadastro(){

		$materias = ["Português","Matemática","Geografia","Física","Inglês","História"];

		$presenca = ['OK','Falta'];

		$tudo = [];

		for ($i=0; $i < 1000 ; $i++) { 

			$idade = mt_rand(16,65);

			$nome = geraNome();

			$genero = ["Masculino","Feminino"];

			$telefone = mt_rand(9000000,9999999);

			$tudo[] = [
				"nome" => $nome,
				"idade" => $idade,
				"genero" => $genero[mt_rand(0,1)],
				"telefone" => $telefone,
				"materias" => [$materias[mt_rand(0,count($materias)-1)],$materias[mt_rand(0,count($materias)-1)]],
				"id" => $i
			];
		}
		$aprovados = 0;
		$datas = json_decode(geraDatas());


		for ($i=0; $i < count($tudo) ; $i++) { 
			$k = 0;
			while ($k != count($tudo[$i]['materias'])) {
				$tudo[$i]['notas'][] = mt_rand(0,10);
				$k++;
			}

			if (array_sum($tudo[$i]['notas']) > 6) {
				$aprovados++;
			}
		}

		for ($i=0; $i < count($tudo) ; $i++) { 
			$k = 0;
			while ($k != count($tudo[$i]['materias'])) {
				$f = 0;
				while ($f != count($datas)) {
					$tudo[$i]['presenca'][] = mt_rand(0,1);
					$f++;
				}
				
				$k++;
			}
		}

		

		$porcentagem = 20 * count($tudo)/100;

		if ($aprovados < $porcentagem) {
			return geraCadastro();
		}	



		$json = json_encode($tudo,TRUE);
		return $json;

} 

function tabela_nova($dado = null){

	$dado = json_decode($dado,true);
	foreach ($dado as $key => $value) {
	echo "<tr>
			<td >".$value['nome']."</td>
			<td >".$value['idade']."</td>
			<td >".$value['telefone']."</td>
			<td><button class ='opener' onclick='mostra_dados(".$value['id'].")' onclick='teste(".$value['id'].")'>Mostrar Dados</button></td>


		</tr>".PHP_EOL;
	}


}

