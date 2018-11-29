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

    function filter(callable $anonimo = null){
    	$filtrado = [];

        var_dump($anonimo);
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
            $sorter = function($a,$b) use ($coluna){
                return $a[$coluna] > $b[$coluna];
            };
        } else {
            $sorter = function($a,$b) use ($coluna){
                return $a[$coluna] < $b[$coluna];
            };
        }

        uasort($this->items,$sorter);

        return $this;
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


class EqFilter{     

    var $chave;
    var $valor;

    function __construct($chave,$valor){
        $this->chave = $chave;
        $this->valor = $valor;
    }

    function __invoke(array $item){
        return $item[$this->chave] == $this->valor;
    }

}


class GtFilter{     

    var $chave;
    var $valor;

    function __construct($chave,$valor){
        $this->chave = $chave;
        $this->valor = $valor;
    }

    function __invoke(array $item){
        return $item[$this->chave] > $this->valor;
    }

}



class LtFilter{     

    var $chave;
    var $valor;

    function __construct($chave,$valor){
        $this->chave = $chave;
        $this->valor = $valor;
    }

    function __invoke(array $item){
        return $item[$this->chave] < $this->valor;
    }

}

class DatasetItem {

    var $get;
    var $func;

    function get($get){
        return  $this->get = $get;
    }

    function eq($valor){
        $func = function($item) use ($valor){
            return $item[$this->get] == $valor;
        };

        return $func;
    }

    function contains($valor){

        $func = function($item) use ($valor){
            return strstr($item[$this->get], $valor);
        };

        return $func;
    }


    function between($min,$max){
        $func = function($item) use ($min,$max){
            return $item[$this->get] >= $min && $item[$this->get] <= $max;
        };

        return $func;
    }
}

