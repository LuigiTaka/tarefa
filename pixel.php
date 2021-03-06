<?php
require_once(__DIR__.'/utils.php');



$page = new Page();
$page->title = 'Pixel tool';
$page->css->jqueryui = true;

$lines = isset($_GET['lines'])?$_GET['lines']:8;
$cols = isset($_GET['cols'])?$_GET['cols']:15;
$total = $lines * $cols;
$teste = [];

$colors = ['Black','Red','Green','Blue'];
$tools = ['Pix','Line','Area'];
$tool = [];
$data = [
    'color' => 'Black',
    'pixels' => [],
    'zoom' => 0,
    'tool' => 'Pix'
];


if(isset($_GET['data'])){
    $data = json_decode($_GET['data'],TRUE);
}

if(isset($_GET['tool'])){
    $data['tool'] = $_GET['tool'];
}

if(isset($_GET['color'])){
    $data['color'] = $_GET['color'];
}

if(isset($_GET['zoom'])){
    $data['zoom'] += $_GET['zoom']=='in' ? +1 : -1;
}

if(isset($_GET['click'])){
    list($l,$c) = explode('-',$_GET['click']);
    if(empty($data['pixels'][$l][$c])){
        $data['pixels'][$l][$c] = $data['color'];
    } else {
        unset($data['pixels'][$l][$c]);
    }

    if (isset($_GET['tool'])) {
        $tool[] = [$l,$c];
            if ($_GET['tool'] == "Line") {
            
                for ($linha=1; $linha <= $lines ; $linha++) {  

                     for ($coluna=1; $coluna <=$cols ; $coluna++) { 
                        if (array_key_exists($linha, $data['pixels']) and array_key_exists($coluna, $data['pixels'][$linha])) {
                  
                            if ($tool[0][1] < $coluna) {

                                for ($i=$coluna; $i > $tool[0][1] ; $i--) { 
                                    if (!array_key_exists($i, $data['pixels'][$linha])) {
                                        
                                        $data['pixels'][$tool[0][0]][$i] = $data['color'];
                                       
                                    }
                                     
                                }

                            }elseif ($tool[0][1] > $coluna) {
                                for ($i=$coluna; $i <= $tool[0][1] ; $i++) { 
                                    
                                    if (!array_key_exists($i, $data['pixels'][$linha])) {
                                        $data['pixels'][$tool[0][0]][$i] = $data['color'];

                                    }
                                
                                }

                            }elseif ($coluna == $tool[0][1] and $linha < $tool[0][0]) {
                                for ($i=$linha; $i < $tool[0][0] ; $i++) { 
                                     $data['pixels'][$i][$coluna] = $data['color'];
                                }
                            }elseif ($coluna == $tool[0][1] and $linha > $tool[0][0]) {

                                for ($i=$linha; $i > $tool[0][0] ; $i--) { 
                                     $data['pixels'][$i][$coluna] = $data['color'];
                                }
                            }
                 
                        }
                    }      
                } 

        }elseif ($_GET['tool'] == 'Area') {
            for ($linha=1; $linha <= $lines ; $linha++) {  

                for ($coluna=1; $coluna <=$cols ; $coluna++) { 
                    if (array_key_exists($linha, $data['pixels']) and array_key_exists($coluna, $data['pixels'][$linha])) {
                        for ($i=$linha; $i <= $tool[0][0] ; $i++) { 
                            
                            for ($j=$coluna; $j <= $tool[0][1] ; $j++) { 
                                $data['pixels'][$i][$j] = 'red';
                            }
                        }
                    }

                }
            }

        }
    }
    
}

if (!empty($_GET['codigo'])) {

    $f = $_GET['codigo'];

    $coisa  = explode('A', $f,3);
    $quadrados =array_pop($coisa);
    $lines = $coisa[0];
    $cols = $coisa[1];
    $quadrados = explode('A', $quadrados);


    $linhas = [];
    $colunas = [];
    foreach ($quadrados as $key => $value) {
       if ($key%2 != 0) {
           $linhas[] = $value; 
       }elseif ($key%2 == 0) {
           $colunas[] = $value;
       }
    }

    // echo "<p>LINHAS :".var_dump($linhas)."</p>";
    // echo "<br>";
    // echo "<p>COLUNAS :".var_dump($colunas)."</p>";

     $k = array_combine($linhas,$colunas);


    //var_dump($k);
    foreach ($k as $key => $value) {
        $data['pixels'][$value][$key] = $data['color'];
    }
    
}


if (!empty($_GET['vezes'])) {
    $vezes = $_GET['vezes'];
    for ($i=1; $i <= $vezes ; $i++) { 
        $data['pixels'][mt_rand(1,$lines)][mt_rand(1,$cols)] = $colors[mt_rand(0,count($colors)-1)];
    }
}


$controle = 0;
for ($j=1; $j <= $lines ; $j++) { 

    if (array_key_exists($j, $data['pixels'])) {
       
        $controle +=  count($data['pixels'][$j]);
    }
      
}

$index = 0;
for ($i=1; $i <=$lines ; $i++) { 
      
    for ($x=1; $x <=$cols ; $x++) { 
        if (array_key_exists($i, $data['pixels']) and array_key_exists($x, $data['pixels'][$i])) {
            $index++;
            
            $teste[$index] = ['A'.$i,'A'.$x];
        }

    }
   
}

if (isset($_GET['posicao'])) {
    if ($_GET['posicao'] == 'up') {

        for ($i=1; $i <=$lines ; $i++) { 

            for ($t=1; $t <=$cols ; $t++) { 

                if (array_key_exists($i, $data['pixels']) and array_key_exists($t, $data['pixels'][$i])) {
                    $color = $data['pixels'][$i][$t];
                    unset($data['pixels'][$i][$t]);
                    if ($i-1 != 0 and empty($data['pixels'][$i-1][$t])) {
                        $data['pixels'][$i-1][$t] = $color;
                    }else{
                       $data['pixels'][$i][$t]= $color;
                    }
                    
                }


            }
        } 

    }elseif($_GET['posicao'] == 'left') {

        for ($i=1; $i <=$lines ; $i++) { 

            for ($t=1; $t <=$cols ; $t++) { 

                if (array_key_exists($i, $data['pixels']) and array_key_exists($t, $data['pixels'][$i])) {
                    $color = $data['pixels'][$i][$t];
                    unset($data['pixels'][$i][$t]);
                    if ($t-1 != 0 and empty($data['pixels'][$i][$t-1])) {
                        $data['pixels'][$i][$t-1] = $color;
                    }else{
                        $data['pixels'][$i][$t] = $color;
                    }
                    
                }
            }
        }  
    }elseif ($_GET['posicao'] == 'down') {

        for ($i=$lines; $i >=1 ; $i--) { 

            for ($t=$cols; $t >= 1 ; $t--) { 

                if (array_key_exists($i, $data['pixels']) and array_key_exists($t, $data['pixels'][$i])) {
                    $color = $data['pixels'][$i][$t];
                    unset($data['pixels'][$i][$t]);
                    if ($i+1 <= $lines and empty($data['pixels'][$i+1][$t])) {
                        $data['pixels'][$i+1][$t] = $color;
                    }else{
                        $data['pixels'][$i][$t] = $color;
                    }
                    
                }
            }
        } 
    }else{
        for ($i=$lines; $i >=1 ; $i--) { 

            for ($t=$cols; $t >= 1 ; $t--) { 

                if (array_key_exists($i, $data['pixels']) and array_key_exists($t, $data['pixels'][$i])) {
                    $color = $data['pixels'][$i][$t];
                    unset($data['pixels'][$i][$t]);
                    if ($t+1 <= $cols and empty($data['pixels'][$i][$t+1])) {
                       $data['pixels'][$i][$t+1] = $color;
                    }else{
                        $data['pixels'][$i][$t] = $color;
                    }

                }
            }
        } 
    }
}



$cd = $lines."A".$cols;

$conta = count($teste);       
foreach ($teste as $key) {
    foreach ( $key as $f => $t) {
      $cd =  $cd . $t;
    
    }
}

ob_start();
?>

<form method="GET">
    <div class="formulario">
        <button name="zoom" value="in"><span class="ui-button ui-icon ui-icon-circle-plus"></span></button>
        <button name="zoom" value="out"><span class="ui-button ui-icon ui-icon-circle-minus"></span></button>

        <input type="number" name="lines" value="<?php echo $lines; ?>">
        x <input type="number" name="cols" value="<?php echo $cols ?>">

        

        <select name="color">
            <?php foreach($colors as $c) : ?>
                <option value="<?php echo $c; ?>" <?php if($c==$data['color']) echo 'selected'; ?>><?php echo $c ?></option>
            <?php endforeach; ?>
        </select>

        <select name="tool">
            <?php foreach($tools as $t) : ?>
                <option value="<?php echo $t; ?>" <?php if($t==$data['tool']) echo 'selected'; ?>><?php echo $t ?></option>
            <?php endforeach; ?>
        </select>

        Pixels aleatórios : <input type="number" name="vezes" max="<?php echo $cols*$lines ?>">

        Código para compartilhar : <input type="text"  value="<?php echo $cd; ?>" id="myInput"> 
        <button onclick="myFunction()" onmouseout="outFunc()">Copiar código</button>
   
        Insira o código : <input type="text" name="codigo" placeholder="Digite o cogido..." class="codigo">
        
        <hr/>

        <table border="1" style="display:inline-block;vertical-align:text-top;">

            <tr>
                <th>\</th>
                <?php for ($t=1; $t <= $cols; $t++) : ?>
                    <th class="id" ><?php if ($cols <= 26) {
                        echo chr(64+$t);
                    }else{
                        echo $t;
                    } ?> </th>
                <?php endfor; ?>
            </tr>

            <?php for($i=1;$i<=$lines;$i++) : ?>

                <tr>
                    <th class="id" scope="row"><?php echo $i; ?></th> 
                    <?php for($j=1;$j<=$cols;$j++) : ?>

                        <td class="<?php echo isset($data['pixels'][$i][$j]) ? $data['pixels'][$i][$j] : ''; ?>">
                            <button name="click" value="<?php echo $i.'-'.$j ?>">&nbsp;</button>
                        </td>

                    <?php endfor; ?>
                </tr>

            <?php endfor; ?>
    </div>

    </table>

    <div style="display:inline-block; vertical-align: text-top;">

        <table id="moveControls">
            <tr>
                <td></td>
                <td><button name="posicao" value="up"><span class="ui-icon ui-icon-circle-arrow-n"></span></button></td>
                <td></td>
            </tr>
            <tr>
                <td><button name="posicao" value="left"><span class="ui-icon ui-icon-circle-arrow-w"></span></button></td>
                <td align="center" style="font-size:26px;"></td>
                <td><button name="posicao" value="right"><span class="ui-icon ui-icon-circle-arrow-e"></span></button></td>
            </tr>
            <tr>
                <td></td>
                <td><button name="posicao" value="down"><span class="ui-icon ui-icon-circle-arrow-s"></span></button></td>
                <td></td>
            </tr>
        </table>

    </div>


    <input type="hidden" name="data" value='<?php echo json_encode($data) ?>' />

</form>
<div class="minus icon"></div>
<small>
    <?php echo $controle; ?> pixels de:  <?php echo $total ; ?>
</small>


<?php $cell_size = 30 + ($data['zoom']*5) ?>

<style>


    table{
        border-collapse: collapse;
    }
    td{
        width:<?php echo $cell_size ?>px;
        padding:0px;
        margin:0px;
    }
    td button{
        background:transparent;
        border:none;
        width:<?php echo $cell_size ?>px;
        height:<?php echo $cell_size ?>px;
        cursor:pointer;
    }
    <?php foreach($colors as $c) : ?>
    td.<?php echo $c; ?> button{
        background: <?php echo $c; ?> !important;
    }
    <?php endforeach; ?>

    .id{
        border: none;
        text-align: center;
        color: #77bfca;

    }

    input  {
        width: 50px;
    }

    .codigo {
        width: 100px;
    }


</style>

<script>
function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  document.execCommand("copy");
  
  var tooltip = document.getElementById("myTooltip");
  tooltip.innerHTML = "Copied: " + copyText.value;
}

</script>
<?php 
$page->content = ob_get_clean();
echo $page;
