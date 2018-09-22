<?php
require_once(__DIR__.'/utils.php');

$page = new Page();
$page->title = "Leitor de Textos";

$atual = isset($_GET['page']) ? $_GET['page'] : 1;

if (isset($_GET['id_obra'])) { #Pra mostrar titulo e autor na Div.

    if(isset($_GET['init'])){

        $checa = getRegistro('status_obra','id_obra',$_GET['id_obra']);

        if ($checa) {

            redirect("?id_obra=".$_GET['id_obra']."&page=".$checa['page']);

        }else{

            $adciona = addRegistro('status_obra',['id_obra' => $_GET['id_obra'],'page' => $atual]);

            redirect("?id_obra=".$_GET['id_obra']."&page=1");

        }

    }

    if (isset($_POST['anotacoes'])) {
        addRegistro('trechos_obras',['trecho' => $_POST['anotacoes'],'page' => $_GET['page'],'id_obra' => $_GET['id_obra']]);
        unset($_POST['anotacoes']);
    }

    removeRegistro('status_obra','id_obra',$_GET['id_obra']);
    $adciona = addRegistro('status_obra',['id_obra' => $_GET['id_obra'],'page' => $atual]);

    $getTitulo = getRegistro('obras','id',$_GET['id_obra']);
    $getAutor = getRegistro('autores','id', $getTitulo['id_autor']);
    $titulo = $getTitulo['obra'];
    $autor = $getAutor['nome'];

    $page->title .= " - $titulo - $autor";

}else{
    $titulo = 'Selecione uma obra';
    $autor = '';
}



ob_start(); // segura a saída
?>

    <div id="obrasList">
        <table>
            <tbody>
                <?php foreach(getRegistros('obras') as $obra) : ?>
                    <tr class="item <?php if(!empty($_GET['id_obra'])&&$_GET['id_obra']==$obra['id']) echo 'selected' ?>">
                        <td>
                            <a href="?id_obra=<?php echo $obra['id']; ?>&init=1"><?php echo $obra['obra']; ?></a>
                            <span style="color:gray;"><?php echo getRegistro('autores','id',$obra['id_autor'])['nome']; ?></span>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="textReader">
        <h2><?php echo $titulo; ?> </h2>
        <p> <?php echo $autor; ?> </p>
        <hr>
        <?php
            if(!empty($_GET['id_obra'])){
                $obra = getRegistro('obras','id',$_GET['id_obra']);
                $text = getLargeText($obra['id_large_text']);
                $trecho = getRegistro('trechos_obras','page',$_GET['page']);
                $text = strip_tags($text);
                $troca = str_replace($trecho['trecho'],"<mark>".$trecho['trecho']."</mark>", $text);
                $paginas = splitText($troca);
                $Npaginas = count($paginas);
   
                $content = "";
                if ( $atual < $Npaginas and $atual > 0) {
                    $content = $paginas[$atual-1];
                }

         
        ?>
        <div id="scroll">
            <?php echo $content; ?>
        </div>
        <div id="bT">

            <input type="button" onClick="location.href='?id_obra=<?php echo $_GET['id_obra']; ?>&page=<?php echo $atual-1; ?>'" value="<<<" />

            <span id="page_stats">
                <?php echo "Página ".$atual." de ".$Npaginas ?>
            </span>
            <input type="button" onClick="location.href='?id_obra=<?php echo $_GET['id_obra']; ?>&page=<?php echo $atual+1; ?>'" value=">>>" />

        </div>
        <?php } ?>
    </div>

    <div id="anotacoes">
        <form method="POST">
            <input type="text" name="anotacoes">
            <input type="submit">
        </form>
    </div>

     <style>
        a{
            text-decoration: none;
            color: black;
        }
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
        #obrasList{
            display:inline-block;
            vertical-align:text-top;
        }
        #textReader {
            display:inline-block;
            vertical-align:text-top;
            width:500px;
            border: solid 1px black;
            max-height: 60%;
            padding: 25px;
        }
        #bT{
            text-align:center;
        }
        #bT button{
            text-align: center;
        }

        #page_stats{
            display:inline-block;
            margin-left:10px;
            margin-right:10px;
        }

        #textReader h2{
            text-align: center;
        }
        #textReader p {
            margin-left: 70%;
        }
        #scroll{
            overflow: auto;
            height: 400px;
            padding: 25px;
        }
        #scroll {
          animation-duration: 3s;
          animation-name: slidein;
        }
        @keyframes slide {
          from {
            margin-left: 100%;
            width: 300%
          }
          to {
            margin-left: 0%;
            width: 100%;
          }
        }
    </style>

<?php
$page->content = ob_get_clean(); // libera a saída
echo $page;