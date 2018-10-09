<?php 
require_once(__DIR__."/utils.php");
$page = new Page;

$page->title = "Pixel tool";

$coluna = 16;
$linha = 16;

$cor = isset($_POST['cor'])? $_POST['cor']:'black';


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

		<input type="submit" name="">
	</form>
</div>

<hr>

<table id="table">
	<?php 
		for ($c=1; $c <=  $coluna; $c++) { 
			echo "<tr>";
			for ($l=1; $l <=$linha ; $l++) {
				if (isset($_GET['coluna']) and isset($_GET['linha']) and $_GET['coluna'] == $c and $_GET['linha'] == $l  ) {

					echo "<td class='teste'><a href='?coluna=$c&linha=$l' rel='noreferrer'>&nbsp;</a></td>";
					
				}else{
					echo "<td class=''><a href='?coluna=$c&linha=$l' rel='noreferrer'>&nbsp;</a></td>";
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
	    background: <?php echo $cor; ?>;
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

    .vazio {
    	background-color: white;
    }
 	
 	.teste {
 		background-color: <?php echo $cor; ?>;
 	}
</style>

<?php 
$page->content = ob_get_clean();
echo  $page;


