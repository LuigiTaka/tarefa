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

    function filter(Closure $anonimo = null){
    	$filtrado = [];
    	if(!empty($anonimo)){
    		$caminha = (array_map($anonimo, $this->toArray()));
    
    		for ($i=0; $i < count($this->toArray()) ; $i++) { 
    			if ($caminha[$i] == 1) {
    				$filtrado[] = $this->toArray()[$i]; 
    			}
    		}

    		return new Dataset($filtrado);
    	}

    	$anonimo = function(){
    		return (bool)rand(0,1);
		};

    	return $this->filter($anonimo);

    }

    function sort($coluna,$modo){

        if($modo=='ASC'){
            $sorter = function($a,$b){
                return $a[$coluna] > $b[$coluna];
            };
        } else {
            $sorter = function($a,$b){
                return $a[$coluna] < $b[$coluna];
            };
        }

        return uasort($this->items,$sorter);
    }




    function values(string $chave){
        $array = json_encode($this->items);

        $array = json_decode($array,true);

    	return array_column($array, $chave);

    }

    function extract($chave = null){
        $extraido = [];
		if (!empty($chave)) {
			$argumentos = func_get_args();
			

	    	for ($i=0; $i < count($argumentos); $i++) { 
	    		$extraido[$argumentos[$i]] = array_column($this->items, $argumentos[$i]);
	    	}

	    	return new Dataset($extraido);
		}
		
        $chave = 'id';
        $extraido[] = array_column($this->items, $chave);

        return new Dataset($extraido);
		
	}

    function toJSON(){
       return json_encode($this->items);
    }



}


