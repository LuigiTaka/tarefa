<?php

class Dataset{

    var $items = [];

    function __construct(array $items)
    {
        $this->items = $items;
    }

    function toArray(){

        return $this->items;

    }
}