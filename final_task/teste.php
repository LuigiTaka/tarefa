<?php 

require_once("geraCadastro.php");
$json =  geraCadastro();
$datas = geraDatas();
$materias = ["Português","Matemática","Geografia","Física","Inglês","História"];

?>
<!DOCTYPE html>
<html>
<head>
	<title>Planilha de Alunos</title>
	<!-- Jquery -->
	<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
  <!--  -->

  <!-- Jquery UI -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!--  -->

  <!-- CSS Jquery -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <!--  -->

	<script type="text/javascript">

		$(document).ready(function(){

			$( function() {
				$( "#datepicker" ).datepicker();
			});

			var dateFormat = "mm/dd/yy",
			from = $( "#from" )
			.datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
			})
			.on( "change", function() {
				to.datepicker( "option", "minDate", getDate( this ) );
			}),
			to = $( "#to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1,
			})
			.on( "change", function() {
				from.datepicker( "option", "maxDate", getDate( this ) );
			});

			function getDate( element ) {
		      var date;
		      try {
		        date = $.datepicker.parseDate( dateFormat, element.value );
		      } catch( error ) {
		        date = null;
		      }
		 
		      return date;
		    }
			json = <?php echo $json; ?>;


			$("#pesquisa-notas").submit(function(){
				$("#presenca").empty()	
				let nome_aluno = $("#pesquisa-aluno").val()
				let data_inicio = $("#from").val()
				let data_final = $("#to").val()
				$.ajax({
					type:"POST",
					url:"pesquisa_presenca.php",
					data:{"nome":nome_aluno, "json":json,'data-inicio':data_inicio,'data-final':data_final},
					success:function(data){
						$("#presenca").append(data)
					},
					error:function(){
						console.log("fail")
					}
				})

				return false
			})


			$("#checa").submit(function(){
			
				let id
				id = $("#id").val();
				json[id]['nome'] = $("#nome").val();
				json[id]['idade'] = $("#idade").val();
				json[id]['telefone'] = $("#telefone").val();
				json[id]['genero'] = $("#genero").val();
				json[id]['materias'] = $("#materias").val();


				$.ajax({
					type: 'POST',
					url: 'att_tabela.php',
					data: {'json': json},
					success:function(data){

						$("#dados-alunos").empty();
						$("#dados-alunos").append(data);
						$( ".opener" ).on( "click", function() {
							$( "#dialog" ).dialog("open");
						});
						let nomes = []
						for (var i = 0; i < json.length; i++) {
							nomes.push(json[i]['nome'])
						}

						// autocomplete com os dados atualizados
						 $( "#tags" ).autocomplete({
					      source: nomes
					    });												
					},
					error:function(){
						alert('fail')
					}

				})
									
				return false
			})

			

			$(function() {
				$( "#dialog" ).dialog({
					autoOpen: false,
					modal: true,
					width: 400,
					height: 300,


					close: function(){
						$("#checa").empty() 
					}
							
				});

				$( ".opener" ).on( "click", function() {
					$( "#dialog" ).dialog("open");
				});
			});

			// Abas Jquery UI
			$( function() {
				$( "#tabs" ).tabs({
				  collapsible: true
				});
			});
		
			$("#notas").submit(function(){
				let nome = $(".pesquisa").val()
				
				$.ajax({
					type: 'POST',
					url: "pesquisa_nota.php",
					dataType:'json',
					data:{'json':json, 'nome':nome},
					success:function(data){
						console.log(typeof data[0]['materia'])
						let topo = $("#topo-nota th").length
						console.log(data)
						let tabela = document.getElementById("tabela-nota");
						let coluna = tabela.insertRow(-1)
						let cell = coluna.insertCell(-1)
						cell.innerHTML = nome
					

						for (var i = 1; i < topo; i++) {
							cell = coluna.insertCell(-1)
							for (var d = 0; d < data.length; d++) {
								if (typeof data[d]['materia'] === 'string') {
									let string = data[d]['materia']
									string = string.split(',')
									for (var m = 0; m < string.length; m++) {
										if ($("#topo-nota th")[i].innerHTML == string[m]) {
											cell.innerHTML = data[d]['nota']
										}else{
											cell.innerHTML = "N"
										}
									}	
								}else{
									for (var m = 0; m < data[d]['materia'].length; m++) {
										if ($("#topo-nota th")[i].innerHTML == data[d]['materia'][m]) {
											cell.innerHTML = data[d]['nota']
										}else{
											cell.innerHTML = "N"
										}	
									}	
								}
							}
						}
						
																	
					},
					error:function(){
						console.log('errou')
					}
				})

				return false
				
			})

			// Autocomplete quando a pagina carrega

			let nomes = []
			for (var i = 0; i < json.length; i++) {
				nomes.push(json[i]['nome'])
			}
			 $( "#tags" ).autocomplete({
			  source: nomes
			});
				

			// 
		});


		function mostra_dados(id){	
			
			$("#checa").append("<input type='text' name='nome' id='nome' value='"+json[id]['nome']+"'>")
			$("#checa").append("<input type='text' name='idade' id='idade' value='"+json[id]['idade']+"'>")
			$("#checa").append("<input type='text' name='genero' id='genero' value='"+json[id]['genero']+"'>")
			$("#checa").append("<input type='text' name='telefone' id='telefone' value='"+json[id]['telefone']+"'>")
			$("#checa").append("<input type='text' name='materias' id='materias' value='"+json[id]['materias']+"'>")
			$("#checa").append("<input type='text' name='materias' id='id' hidden value='"+json[id]['id']+"'>")
			$("#checa").append("<input type='text' disabled name='materias' id='ntoas'  value='"+json[id]['notas']+"'>")
			$("#checa").append("<hr><input type='submit'>")	
	
		}
		function altera_status(element){
			console.log($(element))
			 if(element.className === 'verde') {
            		element.className = 'vermelho';
        	} else {
           	 element.className = 'verde';
        	}

		}	

		
	</script>


	<style type="text/css">

		*{
			padding: 0px;
			margin: 0px;
		}

		.vermelho{
			background-color: red;
		}

		.verde{
			background-color: green;
		}

		#titulo, #pesquisa{
			position: relative;
			left: 38%;
		}

		#pesquisa input{
			width: 200px;
			font-size: 1em;
			margin: 8px;
		}

		#cabecalho-tabela{
			position: relative;
			margin: 6px;
			left: 40%;
			max-width: 360px;
			box-shadow: 2px 3px 5px 0px rgba(0,0,0,0.75);
			background-color: black;
		}

		#scroll{
			height: 200px;
			overflow: scroll;
			max-width: 420px;
			border: 1px solid black;
			position: relative;
			left: 37%;
			resize: vertical;
			box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
			padding: 18px;
		}

		th{
			padding: 10px;
			background-color: black;
			color: white;
		}

		td{
			padding: 10px;
			margin: 2px;
		}


		#dialog{
			box-shadow: 20px 20px 5px 0px rgba(0,0,0,0.75);
		}

		.bg-color{
			border: none;
			background:transparent;
			cursor:pointer;
		}


	</style>

</head>
<body>
		
<div id="tabs">

	<ul>
	    <li><a href="#tabs-1">Presença</a></li>
	    <li><a href="#tabs-2">Notas</a></li>
	    <li><a href="#tabs-3">dados dos alunos</a></li>
	  </ul>




	<div id="tabs-1">
		<h3>Lista de presença</h3>
		<form id="pesquisa-notas">
			Nome :<input type="text" name="pesquisa" placeholder="Pesquisar aluno..." id="pesquisa-aluno">
			<br>
			<label for="from">From</label>
			<input type="text" id="from" name="from">
			<label for="to">to</label>
			<input type="text" id="to" name="to">
			<input type="submit" name="">
		</form>
		

		<table id="presenca">

			<tr id="topo">
				
				
			</tr>

			<tbody id="corpo">
				
			</tbody>

		</table>
	</div>	

	<div id="tabs-2">

		<div class="ui-widget">
			<form id="notas">
				<label for="tags">Nome: </label>
				<input id="tags" type="text" class="pesquisa">
				<input type="submit" name="">
			</form>
			
		</div>
		<table id="tabela-nota">

			<tr id="topo-nota">
					<th>Aluno</th>
				<?php foreach ($materias as $key => $value): ?>
					<th class="<?php echo $value ?>"><?php echo $value; ?></th>
				<?php endforeach ?>
				
			</tr>

			<tbody id="corpo-nota">
				
			</tbody>

		</table>


	</div>

	<div id="tabs-3">

		<div id="titulo"><h1>Planilha de Alunos</h1></div>

		<div id="pesquisa"><input type="text" name="pesquisa" placeholder="Pesquisar por nome..."></div>

		<div id="cabecalho-tabela">
				<table>
						<tr>
							<th>Nome</th>
							<th>Iadde</th>
							<th>Telefone</th>
							<th>Genero</th>	
						</tr>
				</table>
		</div>
		<div id="scroll">
			<table id="dados-alunos">
				<?php foreach (json_decode($json,true) as $key => $value): ?>
					<tr>
						<td ><?php echo  $value['nome']; ?></td>
						<td ><?php echo $value['idade'] ?></td>
						<td ><?php echo $value['telefone'] ?></td>
						<td><?php echo $value['genero']; ?></td>
						<td ><button class="opener" onclick="mostra_dados(<?php echo $value['id']; ?>)">Editar dados</button></td>
					</tr>
				<?php endforeach ?>
							
			</table>
		</div>
			
	</div>
</div>


<div id="dialog">
	<form id="checa">

		
	</form>
</div>



</body>
</html>