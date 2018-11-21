<?php

class Dataset{

    var $items = [];
    var $filtrado;
    var $extraido;
    var $argumentos;


    function __construct(array $items)
    {
        $this->items = $items;
    }

    function toArray(){

        return $this->items;
    }

    function filter(Closure $anonimo = null){
    	
    	if(!empty($anonimo)){
    		$caminha = (array_map($anonimo, $this->toArray()));
    
    		for ($i=0; $i < count($this->toArray()) ; $i++) { 
    			if ($caminha[$i] == 1) {
    				$this->filtrado[] = $this->toArray()[$i]; 
    			}
    		}

    		return $this->filtrado;
    	}

    	$anonimo = function(){
    		return (bool)rand(0,1);
		};

    	return $this->filter($anonimo);
    }

    function values(string $chave){
	  	if ($this->filtrado) {
	  		return array_column($this->filtrado, $chave);
	  	}
    	return array_column($this->items, $chave);

    }

    function extrai($chave = null){

		if (!empty($chave)) {
			$this->argumentos = func_get_args();
			

	    	for ($i=0; $i < count($this->argumentos); $i++) { 
	    		$this->extraido[$this->argumentos[$i]] = array_column($this->filtrado, $this->argumentos[$i]);
	    	}

	    	return $this->extraido;
		}
		
		$chave = 'id';

		$this->extraido[] = array_column($this->filtrado, $chave);

		return $this->extraido;
	}

	function toJSON(){
		$data = $this->extraido;
		$data =  json_encode($data,JSON_PRETTY_PRINT);
		return $data;
	}

}


