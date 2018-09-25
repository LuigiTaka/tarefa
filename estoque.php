<?php

require_once(__DIR__.'/utils.php');

$pg = new Page();
$pg->title = 'Configuração de Estoque';

ob_start();

?>
<h1>Estoque</h1>
<div style="height:400px;overflow-x:scroll;display:inline-block;">
    <table>
        <thead class="header">
            <th>Obra</th>
            <th>Autor</th>
            <th>Preço</th>
            <th>Qtde</th>
        </thead>
        <?php foreach(getRegistros('obras') as $obra) : ?>
            <tr class="item">
                <td><?php echo $obra['obra']; ?></td>
                <td><?php echo getRegistro('autores','id',$obra['id_autor'])['nome']; ?></td>
                <td><input placeholder="Sem preço" /></td>
                <td><input type="number" placeholder="0" /></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<br/><br/>

<button>Salvar</button>

<?php

$pg->content = ob_get_clean();
echo $pg;
