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
        addRegistro('trechos_obras',['trecho' => trim($_POST['anotacoes']),'page' => $_GET['page'],'id_obra' => $_GET['id_obra']]);
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

    <script type="text/javascript">
        function getSelectionText() {
            var text = "";
            var activeEl = document.activeElement;
            var activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;
            if (
              (activeElTagName == "div") || (activeElTagName == "input" &&
              /^(?:text|search|password|tel|url)$/i.test(activeEl.type)) &&
              (typeof activeEl.selectionStart == "number")
            ) {
                text = activeEl.value.slice(activeEl.selectionStart, activeEl.selectionEnd);
            } else if (window.getSelection) {
                text = window.getSelection().toString();
            }
            console.log(text);
            return text;
        }

        document.onmouseup = document.onkeyup = document.onselectionchange = function() {
          document.getElementById("anotacoes").value = getSelectionText();
        };
    </script>

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
                #$trecho = getRegistros('trechos_obras');

                $text = strip_tags($text);

                $paginas = splitText($text);

                $Npaginas = count($paginas);

                $content = "";

                if ( ($atual < $Npaginas) and ($atual > 0)) {

                    $content = $paginas[$atual-1];
                    foreach (getRegistros('trechos_obras') as $trecho) {
                        if ($_GET['id_obra'] == $trecho['id_obra'] and $_GET['page'] == $trecho['page']) {
                            $content = str_replace($trecho['trecho'], "<mark>".$trecho['trecho']."</mark>", $content);
                        }
                    }
                    
                }

                

         
        ?>
        <div id="scroll" onmousedown="getSelectionText();">
            <div>
                <?php echo $content; ?>
            </div>
            
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

    <div id="ant">
        <form method="POST">
            <textarea id="anotacoes" rows="10" cols="20" name="anotacoes" placeholder="Confira as linhas marcadas..."></textarea>
            <input type="submit" value="Salvar">
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
            width:400px;
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
        #ant {
            display: inline-block;
            vertical-align: text-top;
            widows: 100px;
         }
         #ant input {
            display: block;
            margin-top: 4px;

         }
    </style>

<?php
$page->content = ob_get_clean(); // libera a saída
echo $page;