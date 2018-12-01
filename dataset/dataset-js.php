<script type="text/javascript">
	class Dataset{

		constructor(array){
			this.array = array
		}

		toArray(){
			return this.array
		}


		filtro(chave,valor,tipo){

			if (tipo == 'igual') {
				return new Dataset(this.array.filter(function(value){
					return value[chave] == valor
				}))
			}else if(tipo == 'maior'){
				return new Dataset(this.array.filter(function(value){
					return value[chave] > valor
				}))
			}else if (tipo == 'menor'){
				return new Dataset(this.array.filter(function(value){
					return value[chave] < valor
				}))

			}
			
		}

		ordena(coluna){
			let sorter = function(a,b){
				return a[coluna] > b[coluna]
			}


			this.array.sort(sorter)

			return this
		}

		

	}


	let grito = [
	{nome:'Luigi',idade:20},
	{nome:'Mateus',idade:20},
	{nome:'teste',idade:30},
	{nome:'Amedeia',idade:20}

	]

	let teste = new Dataset(grito)

	//console.log(teste.toArray())


	console.log(teste.filtro('idade',20,'igual').ordena('nome'))
</script>