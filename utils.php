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

function removeRegistro($tipo, $chave ,$posicao){ #tem que garanti permições pra escrever no arquivo caso contrário vai dar uns erros muito loucs que da vontade de dar um soco no computador ^-^
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

function redirect($url){
    echo "<script>window.location.href='$url'</script>";
    die();
}

function splitText($text, $maxCaracteres=1000){
    $tamanho = strlen($text);
    $paginas = [];
    $pagina = '';
    for ($i=0; $i < $tamanho ; $i++) {
        $pagina .= substr($text, $i, 1);
        if (strlen($pagina) == $maxCaracteres) {
            $paginas[] = $pagina;
            $pagina = '';
        }
    }
    if ($pagina) {
        $paginas[] = $pagina;
    }
    return $paginas;
}
//////////////////////////////////
function creObra($a, $b) {
   return $a['obra'] > $b['obra'];
}

function decObra($a,$b){
    return $a['obra'] < $b['obra'];
}
///////////////////////////////////
function creAut($a, $b) {
   return $a['autor'] > $b['autor'];
}

function decAut($a, $b) {
   return $a['autor'] < $b['autor'];
}

function utf8_to_unicode_code($utf8_string)
{
    $expanded = iconv("UTF-8", "UTF-32", $utf8_string);
    return unpack("L*", $expanded);
}
function unichr($u) {
    return mb_convert_encoding('&#' . intval($u) . ';', 'UTF-8', 'HTML-ENTITIES');
}
function compress($str, $xmlsafe = false) {
    $dico = array();
    $skipnum = $xmlsafe?5:0;
    for ($i=0; $i<256;$i++) {
        $dico[chr($i)] = $i;
    }
    if ($xmlsafe) {
        $dico["<"] = 256;
        $dico[">"] = 257;
        $dico["&"] = 258;
        $dico["\""] = 259;
        $dico["'"] = 260;
    }
    $res = "";
    $splitStr = str_split($str);
    //print_r($splitStr);
    $length = count($splitStr);
    $nbChar = 256+$skipnum;
    $buffer = "";
    for ($i=0; $i<=$length;$i++) {
        $current = $splitStr[$i];
        if ($dico[$buffer . $current] !== NULL && $current != "")
        {
            //echo $buffer . ":" . $current . "<br>";
            $buffer .= $current;
        }
        else
        {
            $res .= unichr($dico[$buffer]);
            //echo $dico[$buffer] . ": " . unichr($dico[$buffer]) . "<br>";
            $dico[$buffer . $current] = $nbChar;
            $nbChar++;
            $buffer = $current;
        }
    }
    return $res;
}
function decompress($str, $xmlsafe = false) {
    $xmlsafe = false;
    $skipnum = $xmlsafe?5:0;
    for ($i=0; $i<256;$i++) {
        $dico[$i] = chr($i);
    }
    if ($xmlsafe) {
        $dico["<"] = 256;
        $dico[">"] = 257;
        $dico["&"] = 258;
        $dico["\""] = 259;
        $dico["'"] = 260;
    }
    $splitStr = utf8_to_unicode_code($str);
    $length = count($splitStr);
    $nbChar = 256+$skipnum;
    $buffer = "";
    $chaine = "";
    $result = "";
    for ($i=0; $i <= $length; $i++)
    {
        $code = $splitStr[$i];
        //echo unichr($code) . ": " . $code . "<br>";
        $current = $dico[$code];
        if ($buffer == "")
        {
            $buffer = $current;
            $result .= $current;
        }
        else
        {
            if ($code <= 255+$skipnum)
            {
                $result .= $current;
                $chaine = $buffer . $current;
                $dico[$nbChar] = $chaine;
                $nbChar++;
                $buffer = $current;
            }
            else
            {
                $chaine = $dico[$code];
                if (!$chaine) $chaine = $buffer . substr($chaine,0,1);
                $result .= $chaine;
                $dico[$nbChar] = $buffer . substr($chaine,0,1);
                $nbChar++;
                $buffer = $chaine;
            }
        }
    }
    return $result;
}


/**
 * 
 */
class Page
{
    public $title;
    public $content;
    public $css;

    function __construct()
    {
        $this->css = new PageCss();
    }

    public function render(){
        ?>
        <!DOCTYPE>
        <html>
            <head>
                <title><?php echo $this->title; ?></title>
                <meta charset="UTF-8" /> <!-- isso aqui é importante para que as palavras com acento não saiam com pontos de interrogação -->
                <?php if($this->css->jqueryui) : ?>
                    <link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
                <?php endif; ?>
            </head>
            <body>
                <?php echo $this->content; ?>
            </body>

            <style>
                table .header th{
                    background:black;
                    color:white;
                    text-align:left;
                }
                table tr.item td{
                    background:snow;
                    border-bottom:1px dashed silver;
                }

                table tr.item:hover td{
                    background:oldlace;
                }
            </style>

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

class PageCss{
    var $jqueryui = false;
}
