<?php 
require_once("geraCadastro.php");

if (empty($_POST['nome'])) {
	echo "Nome colocado inválido, digite novamente.";
}

$data_inicio = $_POST['data-inicio'];
$data_final = $_POST['data-final'];
$json = $_POST['json'];
$nome = $_POST['nome'];
$match = [];
$calendario = geraDatas($data_inicio,$data_final);
for ($i=0; $i < count($json) ; $i++) { 
	if ($nome == $json[$i]['nome']) {
		$match[] = $json[$i];
	}
}

$calendario = json_decode($calendario);
$presença = ["OK","FALTA","OK"];

if (is_string($match[0]['materias'])) {
	$materias = explode(",",$match[0]['materias']);
}else{
	$materias = $match[0]['materias'];
}


 ?>


<table>
<tr>
	<th></th>
<?php foreach ($materias as $key => $value): ?>

	<th><?php echo $value; ?></th>
		
<?php endforeach ?>	
</tr>
<?php foreach ($calendario as $key => $value): ?>
		
<tr>
	<th><?php echo $value; ?></th>
	<?php for ($i=0; $i < count($materias) ; $i++) :?>
		<td><button class="bg-color" onclick="altera_status(this)"><?php echo $presença[mt_rand(0,2)]; ?></button></td>
	<?php endfor;  ?>
	
</tr>



<?php endforeach ?>
</table>