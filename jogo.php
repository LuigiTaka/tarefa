<?php 
require_once(__DIR__."/utils.php");

$page = new Page();
$page->title = "Jogo de letras";

$colunas = 6;
$linhas = 6;
$letras = [];
	
$empty_at = [rand(1,6),rand(1,6)];


if (!getRegistro('teste2','id',1)){
    for ($i=0; $i < 36 ; $i++) { 
        $letras[] = chr(rand(65,90));
    }
	addRegistro('teste2',['tabela' => $letras, 'vazio' => $empty_at]);
}

$get = getRegistro('teste2','id',1);



if (!empty($_GET['coluna']) && !empty($_GET['linha'])) {
    settype($_GET['coluna'], "integer");
    settype($_GET['linha'], "integer");
    //$get['vazio'] = [$_GET['coluna'],$_GET['linha']];
   
    if ($_GET['coluna'] == $get['vazio'][0]+1 and $_GET['linha'] == $get['vazio'][1] or
     $_GET['coluna'] == $get['vazio'][0]-1 and $_GET['linha'] == $get['vazio'][1]) {

       $get['vazio'] = [$_GET['coluna'],$_GET['linha']];
       
   }elseif ($_GET['coluna'] == $get['vazio'][0] and $_GET['linha'] == $get['vazio'][1]+1 or 
    $_GET['coluna'] == $get['vazio'][0] and $_GET['linha'] == $get['vazio'][1]-1) {

       $get['vazio'] = [$_GET['coluna'],$_GET['linha']]; 
   }            

    $json = json_decode(file_get_contents(__DIR__.'/database/teste2.json'),true); 

    $json[0]['vazio'] = $get['vazio'];

    file_put_contents(__DIR__.'/database/teste2.json', json_encode($json,JSON_PRETTY_PRINT)); 



}


echo "<br>";



ob_start();

?>


<table id="table">
    <?php 
    	for ($n=1; $n <=$colunas ; $n++) { 
    		echo "<tr>";
	    		for ($f=1; $f <=$linhas ; $f++) {

        			if ($n === $get['vazio'][0] and $f === $get['vazio'][1]) {
        				echo "<td class='empty'></td>";
        			}else{
        				echo "<td><a href='?coluna=$n&linha=$f'>".$get['tabela'][rand(0,count($get['tabela'])-1)]."</a>

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

    #table a{
        text-decoration: none;
    }
    #table a:visited {
        color: black;
    }
</style>

<?php 

$page->content = ob_get_clean();
echo $page;
