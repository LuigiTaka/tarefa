<?php 
require_once(__DIR__."/utils.php");
$page = new Page;

$page->title = "Pixel tool";

$coluna = 16;
$linha = 16;



if (isset($_POST['cor'])) {
	if (!getRegistros('cores')) {

		addRegistro('cores',['cor' => $_POST['cor'] ]);
	}else{
		$json = json_decode(file_get_contents(__DIR__.'/database/cores.json'),true); 

	    $json[0]['cor'] = $_POST['cor'];

	    file_put_contents(__DIR__.'/database/cores.json', json_encode($json,JSON_PRETTY_PRINT)); 
	}
}
$getCor = getRegistro('cores','id',1);	
$cor = isset($_POST['cor'])?$getCor['cor']:'black';

if (isset($_GET['coluna']) and isset($_GET['linha'])) {
	if (!getRegistro('tabela','coluna',$_GET['coluna']) or !getRegistro('tabela','linha',$_GET['linha'])) {
		addRegistro('tabela',['coluna' => $_GET['coluna'], 'linha' => $_GET['linha'], 'cor' => $getCor['cor']]);
	}
	
}


?>


<div>
	<form method="POST">
		Zoom: 
		<a class="zoom" href="">+</a>
		<a class="zoom" href="">-</a>

		&nbsp;&nbsp;

		<select name="cor">
			<option value="red">Red</option>
			<option value="green">Green</option>
			<option value="black">Black</option>
		</select>

		<input type="submit" name="Enviar">
	</form>
</div>

<hr>

<table id="table" >
	<?php 
	
    	for ($n=1; $n <= $coluna ; $n++) { 
    		$teste = getRegistro('tabela','id',$n);
    		echo "<tr>";
	    		for ($f=1; $f <= $linha ; $f++) {

        			if ($teste['coluna'] == $n and $teste['linha'] == $f) {
        				echo "<td class = 'pinta'><a href='?coluna=$n&linha=$f' rel='noreferrer'> . </a></td>";
        			}else{
        				echo "<td ><a href='?coluna=$n&linha=$f' rel='noreferrer'> . </a></td>";

                   }	
	    		}
    		echo "</tr>";	
    	}    	

	 ?>
</table>

<style type="text/css">
	
    body{
        width:9000px;
    }
    #table{
        border-collapse: collapse;
    }
    #table td{
        width:20px;
        height:20px;
        border:1px solid black;
    }
    #table td:hover{
        cursor:pointer;
        background:oldlace;
    }

    #table td:not(.vazio):hover{
	    background: oldlace;
	    cursor:pointer;
    }

    #table a, a::selection{
    	text-align: center;
    	text-decoration: none;
    	color: white;
    }

    .zoom{
        background:snow;
        display:inline-block;
        border:1px solid black;
        width:20px;
        height:20px;
        text-align:center;
        cursor:pointer;
    }
    .zoom:hover{
        background:black;
        color:white;
    }

    .pinta {
    	background-color: <?php echo $getCor['cor']; ?>;
    }

</style>

<?php 
$page->content = ob_get_clean();
echo  $page;


