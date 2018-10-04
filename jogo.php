<?php 
require_once(__DIR__."/utils.php");
session_start();
$page = new Page();

$page->title = "Jogo de letras";

$colunas = 6;
$linhas = 6;
$letras = [];

	
$empty_at = [rand(1,6),rand(1,6)];
	


if (!getRegistro('jogo_letra','id',1)) {
		for ($i=0; $i < 36 ; $i++) { 
			$letras[] = chr(rand(65,90));
		}
		addRegistro('jogo_letra',['tabela' => $letras, 'vazio' => $empty_at]);
}


$get = getRegistro('jogo_letra','id',1);


if (!empty($_GET['linha'] && !empty($_GET['coluna']))) {
    settype($_GET['linha'], "integer");
    settype($_GET['coluna'], "integer");
    $get['vazio'] = [$_GET['coluna'],$_GET['linha']];            
}



ob_start();
?>

<table id="table">
    <?php 
 
    	for ($c=1; $c <=$colunas ; $c++) { 
    		echo "<tr>";
	    		for ($l=1; $l <=$linhas ; $l++) { 

        			if ($c === $get['vazio'][0] and $l === $get['vazio'][1]) {
        				echo "<td class='empty'></td>";
        			}else{
        				echo "<td><a href='?coluna=$c&linha=$l'>".$get['tabela'][rand(0,35)]."</a>
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