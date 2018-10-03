<?php

// Model - carrega estado salvo ou gera novo
if(!$array = load()){

    $array = gera();

    save($array);

}

// Controller - processa comandos para alterar o estado
if(!empty($_GET['clicked_line']) && !empty($_GET['clicked_col'])){

    // Gera novo estado
    $array = move($array, $_GET['clicked_line'], $_GET['clicked_pos']);

    // Salva novo estado
    save($array);

}

// View - transforma o estado num gráfico
render($array);