<?php

require_once(__DIR__.'/utils.php');

$page = new Page();
$page->title = "Leitor de Textos";

ob_start(); // segura a saída

?>

    <div id="obrasList">

    </div>

    <div id="textReader">

    </div>

<?php

$page->content = ob_get_clean(); // libera a saída
$page->render();