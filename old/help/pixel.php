<?php

$lines = 20;
$cols = 20;

$colors = ['Black','Red','Green','Blue'];

$data = [
    'color' => 'Black',
    'pixels' => [],
    'zoom' => 0
];

if(isset($_GET['data'])){
    $data = json_decode($_GET['data'],TRUE);
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
}

?>

<form method="GET">

    <button name="zoom" value="in">+</button>
    <button name="zoom" value="out">-</button>

    <select name="color">
        <?php foreach($colors as $c) : ?>
            <option value="<?php echo $c; ?>" <?php if($c==$data['color']) echo 'selected'; ?>><?php echo $c ?></option>
        <?php endforeach; ?>
    </select>

    <hr/>

    <table border="1">
        <?php for($i=1;$i<=$lines;$i++) : ?>
            <tr>
                <?php for($j=1;$j<=$cols;$j++) : ?>
                    <td class="<?php echo isset($data['pixels'][$i][$j]) ? $data['pixels'][$i][$j] : ''; ?>">
                        <button name="click" value="<?php echo $i.'-'.$j ?>">&nbsp;</button>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>

    <input type="hidden" name="data" value='<?php echo json_encode($data) ?>' />

</form>

<?php $cell_size = 20 + ($data['zoom']*5) ?>

<style>
    form > button{
        width:40px;
    }
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


</style>
