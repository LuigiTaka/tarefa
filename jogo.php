<?php 
require_once(__DIR__."/utils.php");

$page = new Page();

$page->title = "Jogo de letras";

	$colunas = 6;
	$linhas = 6;
	$controle = 12;
	$letras = [];
	for ($i=0; $i < 100 ; $i++) { 
		$letras[] = $random_string = chr(rand(65,90));
	}

	$empty_at = [rand(1,6),rand(1,6)];
	
ob_start();

?>
<table id="table">
    <?php 
    	for ($n=1; $n <=$colunas ; $n++) { 
    		echo "<tr>";
	    		for ($f=1; $f <=$linhas ; $f++) { 
	    			
	    			if ($n === $empty_at[0] and $f === $empty_at[1]) {
	    				echo "<td class='empty'> <a href='?a=0'>".$letras[rand(0,99)]."</a></td>";
	    			}else{
	    				echo "<td value = ".($f+$n+6)."><a href='?a=1'>".$letras[rand(0,99)]."</a> </td>";
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
