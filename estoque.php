<?php
require_once(__DIR__."/utils.php");

$pg = new Page();
$pg->title = 'Configuração de Estoque';

$getObras = getRegistros('obras');
$getEstoque = getRegistros('estoque_controle');

if (!empty($_GET['a']) == "1") {
    $a = usort($getObras,"creObra");
    $link = "?a=0";
}else{
    $a = usort($getObras, "decObra");
    $link = "?a=1";
}




ob_start();

?>
<h1>Estoque</h1>
<div style="height:400px;overflow-x:scroll;display:inline-block;">
<form method="POST">
    <table>
        <thead class="header">
            <th><a href="<?php echo $link; ?>">Obra</a></th>
            <th><a href="">Autor</a></th>
            <th><a href="<?php echo $link2; ?>">Preço</a></th>
            <th>Qtde</th>
        </thead>
        <?php $i = 0; ?>
        <?php foreach($getObras as $obra) : ?>

            <tr class="item">
                <td id="<?php echo $i; ?>"><?php echo $obra['obra']; ?></td>
                <td><?php echo getRegistro('autores','id',$obra['id_autor'])['nome']; ?></td>


                <td><input placeholder="Sem preço" name="p<?php echo $i; ?>" value ="<?php if($a = getRegistro('estoque_controle','id_obra',$obra['id_obra'])) echo $a['preco']; ?>"  /></td>



                <td><input type="number" placeholder="0" name="q<?php echo $i;?>" value="<?php if($a = getRegistro('estoque_controle','id_obra',$obra['id_obra'])) echo $a['qtde']; ?>"/></td>

                <?php 
                if (!empty($_POST['q'.$i]) and !empty($_POST['q'.$i])) {
                     addRegistro('estoque_controle',
                        ['preco'=> $_POST['p'.$i], 'qtde' => $_POST['q'.$i], 'id_obra' => $obra['id_obra']]);
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
