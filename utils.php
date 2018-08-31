<?php

/**
 * Esta função serve para cadastrar qualquer tipo de entidade
 *
 *
 * @param string $tipo
 * @param array $registro
 */
function addRegistro(string $tipo, array $registro){

    $database_dir = __DIR__."/database";

    if(!is_dir($database_dir)){
        mkdir($database_dir);
    }

    $database_file = $database_dir."/$tipo.json";

    
    $registros = [];
  
    if(file_exists($database_file)){
        $registros = json_decode(file_get_contents($database_file),true);
    }


    #$registros = json_decode(file_put_contents($database_file, $registro),true);
    $registro['id'] = count($registros)+1;

    $registros[] = $registro;
    $resultado = array_unique($registros, SORT_REGULAR);

    file_put_contents($database_file, json_encode($registros));


    /*
    fwrite($fp, json_encode($registros));
    fclose($fp);
    */
  
}


function getRegistro(string $tipo, string $atributo, string $valor) {

    $database = __DIR__."/database";


    $database_file = $database."/$tipo.json";

    #$json = [];
   
    

    if (file_exists($database_file)) {
         $json = json_decode(file_get_contents($database_file),true);
    }

    for ($i=0; $i < count($json) ; $i++) { 
       if (array_key_exists($atributo, $json[$i])) {
            if (array_search($valor, $json[$i])) {
                foreach ($json[$i] as $key => $value) {
                    var_dump($json[$i]);
                   return $json[$i];
                }
            }
        }else{
            echo "False";
            return false;
        }
    }

}

