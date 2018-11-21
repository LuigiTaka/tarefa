<?php

require_once(__DIR__.'/Dataset.php');

$pessoas = require __DIR__.'/pessoas.php';

$ds = new Dataset($pessoas);

// O filter deve permitir que a função manipule cada item do array
// pois assim é possível fazer perguntas sobre certos atributos

// Seleciona apenas pessoas que tem ensino técnico e possuem menos de 30 anos
$ds2 = $ds->filter(function(array $item){
   return $item['ensino'] == 'Técnico' && $item['idade'] < 30;
});


//var_dump($ds2);

// O método values deve retornar um array com os valores de um atributo
// printa apenas os ids das pessoas encontradas
print_r($ds->values('id')); // Ex: [10, 12, 224, 850] 