<?php

require_once(__DIR__.'/Dataset.php');

$pessoas = require __DIR__.'/pessoas.php';

$ds = new Dataset($pessoas);

/*
LEGENDA:
  EqFilter -> Equal (Igual)
  GtFilter -> Greater Than (Maior que)
  LtFilter -> Less Than (Menor que)
*/

// Pessoas do ensino fundamental, idade maior que 20 e peso menor que 70
$result = $ds->filter(new EqFilter('ensino','Fundamental'))
             ->filter(new GtFilter('idade',20))
             ->filter(new LtFilter('peso',70));

/*
Dica: crie as classes EqFilter, GtFilter e LtFilter, que devem implementar o método mágico __invoke()
*/

print_r($result->extract('id,nome,genero')->toArray());