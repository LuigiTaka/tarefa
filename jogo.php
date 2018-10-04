<?php 
require_once(__DIR__."/utils.php");
session_start();
$page = new Page();
$_SESSION['player'] = "Lima Barreto";//getRegistro('autores','id',rand(1,6))['nome'];
$page->title = "Jogo de letras";

	$colunas = 6;
	$linhas = 6;
	$found = 1;
	$letras = [];
	for ($i=0; $i < 36 ; $i++) { 
		$letras[] = chr(rand(65,90));
	}

$empty_at = [rand(1,6),rand(1,6)];


if (getRegistro('teste_jogo','nome',$_SESSION['player'])){
	echo "Logou como ".$_SESSION['player'];
}

$get = getRegistro('teste_jogo','nome',$_SESSION['player']);

if (!empty($_GET['linha'] && !empty($_GET['coluna']))) {
    settype($_GET['linha'], "integer");
    settype($_GET['coluna'], "integer");
    $get['vazio'] = [$_GET['coluna'],$_GET['linha']];            
}
echo "<br>";
var_dump($get['vazio']);

ob_start();

?>
<div>
	<p>Logado como &#8611; <?php echo $_SESSION['player']; ?></p>
</div>

<table id="table">
    <?php 
 
    	for ($n=1; $n <=$colunas ; $n++) { 
    		echo "<tr>";
	    		for ($f=1; $f <=$linhas ; $f++) { 
        			if ($n === $get['vazio'][0] and $f === $get['vazio'][1]) {
        				echo "<td class='empty'></td>";
        			}else{
        				echo "<td><a href='?linha=$f&coluna=$n'>".$get['tabela'][rand(0,35)]."</a>

                        </td>";
		    		}	
	    		}
    		echo "</tr>";	
    	}		


        
    
    	
     ?>
</table>


    <style type="text/css">
     body{
        background:gainsboro;
    }


    #table{
        border:1px solid black;
    }
    #table td{
        width:50px;
        height:50px;
        border:1px solid black;
        text-align:center;
        background:white;
        text-transform: uppercase;
    }

    #table td.empty{
        background:silver;
        color:black;
    }

    #table td:not(.empty):hover{
        background:oldlace;
        cursor:pointer;
    }


    </style>
<?php 

$page->content = ob_get_clean();
echo $page;
