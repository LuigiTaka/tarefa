<?php
require_once(__DIR__."/utils.php");

$pg = new Page();
$pg->title = 'Configuração de Estoque';

$getObras = getRegistros('obras');
$getEstoque = getRegistros('estoque_controle');


$infos = [];
foreach ($getObras as $obra) {
    $infos[] = ['obra' => $obra['obra'],
    'autor' => getRegistro('autores','id',$obra['id_autor'])['nome'],
    'preco' => getRegistro('estoque_controle','id_obra',$obra['id_obra'])['preco'],
    'qtde' => getRegistro('estoque_controle','id_obra',$obra['id_obra'])['qtde'], 
    'id_obra' => $obra['id_obra']];
}

if(!empty($_GET['sort_by'])){

    if($_GET['sort_order']=='ASC'){
        $sorter = function($a,$b){
            return $a[$_GET['sort_by']] > $b[$_GET['sort_by']];
        };
    } else {
        $sorter = function($a,$b){
            return $a[$_GET['sort_by']] < $b[$_GET['sort_by']];
        };
    }

    uasort($infos,$sorter);

}

function geraUrlOrdenacao($coluna){
    if(!empty($_GET['sort_by']) && $_GET['sort_by']==$coluna){
        $order = $_GET['sort_order']=='ASC' ? 'DESC' : 'ASC';
    } else {
        $order = 'ASC';
    }
    return "?sort_by=$coluna&sort_order=$order";
}

ob_start();

?>

<h1>Estoque</h1>
<div style="height:400px;overflow-x:scroll;display:inline-block;">
<form method="POST">
    <table>
        <thead class="header">
            <th>
                <a href="<?php echo geraUrlOrdenacao('obra'); ?>">Obra</a>
            </th>

            <th>
                <a href="<?php echo geraUrlOrdenacao('autor'); ?>">Autor</a>
            </th>
            <th>
                <a href="<?php echo geraUrlOrdenacao('preco') ?>">Preço</a>
            </th>

            <th>Qtde</th>
        </thead>
        <?php $i = 0; ?>
        <?php foreach($infos as $obra) : ?>

            <tr class="item">
                <td ><?php echo $obra['obra']; ?></td>
                <td><?php echo $obra['autor']; ?></td>

                <td><input placeholder="Sem preço" name="p<?php echo $i; ?>" value ="<?php if ($obra['preco'] != null) : echo $obra['preco'];?><?php endif ?>"  /></td>

                <td><input type="number" placeholder="0" name="q<?php echo $i;?>" value="<?php if ($obra['qtde'] != null) : echo $obra['qtde'];?><?php endif ?>"/></td>

                <?php 
                if (!empty($_POST['p'.$i]) and !empty($_POST['q'.$i])) {
                     addRegistro('estoque_controle',['preco'=> $_POST['p'.$i],
                        'qtde' => $_POST['q'.$i],
                        'id_obra' => $obra['id_obra']]);
                }
               
                ?>
            </tr>
        
        <?php $i++;
            
         ?>
        <?php endforeach; ?>


    </table>
</div>

<br/><br/>

<button>Salvar</button>
</form>

<style>
    a {
        text-decoration: none;
        color: white;
    }
</style>
<?php


$pg->content = ob_get_clean();
echo $pg;
