<?php

class Dataset{

    var $items = [];
    var $filtrado;


    function __construct(array $items)
    {
        $this->items = $items;
    }

    function toArray(){

        return $this->items;

    }


    function filter(Closure $anonimo=null){
    	
    	if (isset($anonimo)) {
    		$caminha = (array_map($anonimo, $this->items));
   
    		for ($i=0; $i < count($this->items) ; $i++) { 
    			if ($caminha[$i] == 1) {
    				$this->filtrado[] = $this->items[$i]; 
    			}
    		}
    		
    		return $this->filtrado;
    	}

    	return $this->items;
	
    }

    

}


