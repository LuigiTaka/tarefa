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

    file_put_contents($database_file, json_encode($registros, JSON_PRETTY_PRINT));

    return $registro;

    /*
    fwrite($fp, json_encode($registros));
    fclose($fp);
    */
  
}


function getRegistro(string $tipo, string $atributo, string $valor) {

    $database = __DIR__."/database";
    
    $database_file = $database."/$tipo.json";

    $json = [];

    if (file_exists($database_file)) {
        $json = json_decode(file_get_contents($database_file, JSON_PRETTY_PRINT), true);
    }

    $valor = trim($valor);

    for ($i=0; $i < count($json) ; $i++) { 
        if (trim($json[$i][$atributo])==$valor) {
            return $json[$i];
        }
    }

    return false;

}

function getRegistros(string $tipo){

    $database = __DIR__."/database";

    $database_file = $database."/$tipo.json";

    if(!file_exists($database_file)){
        return [];
    }

    return json_decode(file_get_contents($database_file),true);

}

function addLargeText($text){

    $database = __DIR__."/database/textos";

    if (!is_dir($database)) {
        mkdir($database);
    }

    $id = md5($text);

    $database_file = $database."/$id.txt";

    file_put_contents($database_file, $text);

    return $id;
    
}

function getLargeText($id){
   return  file_get_contents(__DIR__."/database/textos/$id.txt");
}

function remove($tipo, $chave ,$posicao){ #tem que garanti permições pra escrever no arquivo caso contrário vai dar uns erros muito loucs que da vontade de dar um soco no computador ^-^
     $database = __DIR__."/database";
    
    $database_file = $database."/$tipo.json";

    $json = [];


    if (file_exists($database_file)) {
        $json = json_decode(file_get_contents($database_file, JSON_PRETTY_PRINT), true);
    }


      for ($i=0; $i < count($json) ; $i++) { 
        if (trim($json[$i][$chave])==$posicao) {
            unset($json[$i]);
        }
    }

    $certo = array_values($json);
    
    #é
    
    file_put_contents($database_file, json_encode($certo,JSON_PRETTY_PRINT));

}


/**
 * 
 */
class Page
{
    public $title;
    public $content;

    public function render(){
        ?>
        <!DOCTYPE>
        <html>
            <head>
                <title><?php echo $this->title; ?></title>
                <meta charset="UTF-8" /> <!-- isso aqui é importante para que as palavras com acento não saiam com pontos de interrogação -->
            </head>
            <body>
                <?php echo $this->content; ?>
            </body>
        </html>
        <?php
    }


    public function __toString()
    {
        ob_start();
        $this->render();
        return ob_get_clean();
    }

}
