<?php

require_once(__DIR__.'/../utils.php');

$obra = getRegistro('obras','obra','Senhora');
$text = getLargeText($obra['id_large_text']);

$chars_per_page = 1000;
$pages = [];
$length = strlen($text);
$page = '';

for($i=0;$i<$length; $i++){
    $page .= substr($text, $i, 1);
    if(strlen($page)==$chars_per_page){
        $pages[] = $page;
        $page = '';
    }
}

if($page){
    $pages[] = $page;
}

$num_pages = count($pages);

$current = isset($_GET['page']) ? $_GET['page'] : 1;

ob_start();
?>

<h1>Senhora - José de Alencar</h1>

<fieldset style="height:400px;overflow-y:auto;width:800px;">
    <legend>
        Página <?php echo $current; ?> de <?php echo $num_pages ?>
        <a href="?page=<?php echo $current+1; ?>">Próxima &raquo;</a>
    </legend>
    <?php echo $pages[$current-1]; ?>
</fieldset>

<?php

$pg = new Page();
$pg->title = 'Help - Paginação';
$pg->content = ob_get_clean();

echo $pg;

