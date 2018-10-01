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

if (!empty($_GET['a']) == "1") {
    $a = usort($infos,"creObra");
    $link = "?a=0";
}else{
    usort($infos, "decObra");
    $link = "?a=1";
}

if (!empty($_GET['b'])== "1") {
    usort($infos, "creAut");
    $link2 = "?b=0";
}else{
    usort($infos, "decAut");
    $link2 = "?b=1";
}

ob_start();

?>
<h1>Estoque</h1>
<div style="height:400px;overflow-x:scroll;display:inline-block;">
<form method="POST">
    <table>
        <thead class="header">
            <th>
                <a href="<?php echo $link; ?>">Obra</a>
            </th>

            <th>
                <a href="<?php echo $link2; ?>">Autor</a>
            </th>
            <th>
                <a href="<?php echo $link3 ?>">Preço</a>
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
