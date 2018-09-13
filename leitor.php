<?php

require_once(__DIR__.'/utils.php');

$page = new Page();
$page->title = "Leitor de Textos";
if (isset($_GET['id_obra'])) { #Pra mostrar titulo e autor na Div.
    $getTitulo = getRegistro('obras','id',$_GET['id_obra']);
    $getAutor = getRegistro('autores','id', $getTitulo['id_autor']);
    $titulo = $getTitulo['obra'];
    $autor = $getAutor['nome'];

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
        <h2><?php echo $titulo; ?> </h2>
        <p> <?php echo $autor; ?> </p>
        <hr>
        <?php
            if(!empty($_GET['id_obra'])){
                $obra = getRegistro('obras','id',$_GET['id_obra']);
                $text = getLargeText($obra['id_large_text']);

                $tamanho = strlen($text);
                $maxCaracteres = 1000;
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

                $Npaginas = count($paginas);

                $atual = isset($_GET['page']) ? $_GET['page'] : 1;
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

            <?php echo "Página ".$atual." de" ?>
            <input disabled value="<?php echo $Npaginas-1 ?>">

            <input type="button" onClick="location.href='?id_obra=<?php echo $_GET['id_obra']; ?>&page=<?php echo $atual+1; ?>'" value=">>>" />

        </div>
        <?php } ?>
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

        #bT button,input{
            width: 10%;
            text-align: center;
            margin: 10px;
        }

        #textReader h2{
            text-align: center;

        }

        #textReader p {
            margin-left: 70%;
        }

        #scroll{
            overflow: auto;
            max-height: 25%;
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