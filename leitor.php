<?php

require_once(__DIR__.'/utils.php');

$page = new Page();
$page->title = "Leitor de Textos";

ob_start(); // segura a saída

?>

    <div id="obrasList">
        <table>
            <tbody>
                <?php foreach(getRegistros('obras') as $obra) : ?>
                    <tr class="item <?php if(!empty($_GET['id_obra'])&&$_GET['id_obra']==$obra['id']) echo 'selected' ?>">
                        <td>
                            <a href="?id_obra=<?php echo $obra['id']; ?>"><?php echo $obra['obra']; ?></a>
                            <span style="color:gray;"><?php echo getRegistro('autores','id',$obra['id_autor'])['nome']; ?></span>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="textReader">
        <?php
            if(!empty($_GET['id_obra'])){
                $obra = getRegistro('obras','id',$_GET['id_obra']);
                $text = getLargeText($obra['id_large_text']);
                echo $text;
            }
        ?>
    </div>

    <style>
        .item td{
            background:snow;
            border-bottom:1px dashed silver;
            padding:2px;
        }
        .item:hover td{
            background: oldlace;
        }
        .item.selected td{
            background:yellow;
        }

    </style>

<?php

$page->content = ob_get_clean(); // libera a saída
echo $page;