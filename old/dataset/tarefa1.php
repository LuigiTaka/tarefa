<?php
require_once(__DIR__.'/Dataset.php');
$pessoas = require __DIR__.'/pessoas.php';
$ds = new Dataset($pessoas);
// O método filter sempre retorna um novo objeto Dataset com o array filtrado
// Seleciona algumas pessoas aleatoriamente
$ds2 = $ds->filter(function(){
    return (bool)rand(0,1); // se true seleciona, se false não seleciona
});

//var_dump($ds2);

// Ordena por nome ascendente e converte em array
//print_r($ds2->sort('nome','ASC')->toArray());

