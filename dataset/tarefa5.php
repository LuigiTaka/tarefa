<?php

require_once(__DIR__.'/Dataset.php');

$pessoas = require __DIR__.'/pessoas.php';

$ds = new Dataset($pessoas);

$result = $ds->filter(function(DatasetItem $item){
    $ensino = $item->get('ensino');
    $estado = $item->get('estado');
    $idade = $item->get('idade');

    return ($ensino->eq('Superior') || $ensino->eq('Médio')) && $estado->contains('Sant') && $idade->between(20, 33);

});


echo $result->extract('id,nome,estado,idade')->sort('nome','ASC')->toJSON();