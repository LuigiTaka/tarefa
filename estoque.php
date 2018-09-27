<?php

require_once(__DIR__.'/utils.php');



$pg = new Page();
$pg->title = 'Configuração de Estoque';

$getObras = getRegistros('obras');
$getEstoque = getRegistros('estoque_controle');

//teste



for ($i=0; $i <count($getObras) ; $i++) { 
    if (!empty($_POST['preco'.$i])) {
        $estoque = addRegistro('estoque_controle',['preco' => $_POST['preco'.$i],'qtde' => $_POST['qtde'.$i], 'obra' => $getObras[$i]['obra'] ]);
    }

    

    unset($_POST['preco'.$i]);
    unset($_POST['qtde'.$i]);
    
}


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
            <th><a href="<?php echo $link ?>">Obra</a></th>
            <th><a href="">Autor</a></th>
            <th><a href="">Preço</a></th>
            <th>Qtde</th>
        </thead>
        <?php $i = 0; ?>
        <?php foreach($getObras as $obra) : ?>
        
            <tr class="item">
                <td><?php echo $obra['obra']; ?></td>
                <td><?php echo getRegistro('autores','id',$obra['id_autor'])['nome']; ?></td>
                <td><input placeholder="Sem preço" name="preco<?php echo $i; ?>" value ="<?php if($a = getRegistro('estoque_controle','obra',$obra['obra'])) echo $a['preco']; ?>" /></td>
                <td><input type="number" placeholder="0" name="qtde<?php echo $i;?>" value="<?php if($a = getRegistro('estoque_controle','obra',$obra['obra'])) echo $a['qtde']; ?>" /></td>
            
            </tr>
        
        <?php $i+=1;
        
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
