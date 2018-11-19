<?php

require_once(__DIR__.'/Dataset.php');

$pessoas = require __DIR__.'/pessoas.php';

$ds = new Dataset($pessoas);

$filter = function(array $item){
    return $item['ensino'] == 'Superior' && $item['genero'] == 'F';
};

// Filtra, extrai apenas id e nome, converte para json
$json = $ds->filter($filter)->extract('id,nome')->toJSON();

echo $json;