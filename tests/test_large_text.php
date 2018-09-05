<?php 
include_once  __DIR__."\..\utils.php";

$conteudo = file_get_contents("http://htput.com/luigi_project/obra-100");
$id_gerado = addLargeText($conteudo);

if(getLargeText($id_gerado) == $conteudo){
    echo "Funcionou!!";
} else {
    echo "Não funciona :_(";
}