<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
   
        <body>
        <div data-role="page" id="permutar_itens">  
           
            <div data-role="header">
			
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
				<h1>Permutar</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
				
				<ul data-role="listview" id="list_Title" >
					<li  data-role="list-divider" id="title"> 
						
					</li>
				</ul>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content">
			
				<ul data-role="listview" data-icon="false" id="list_PermutarItens"  >
					<li style='padding:0;background-color:#dddddd;' class="linhatodos">
						<a href="#"  style="padding:0;background-color:#dddddd;border-color:#dddddd;">
							<form> 
								<fieldset data-role="controlgroup" style="display:inline;border:none;"> 
									<label  style="border:none;background-color:#dddddd;" id="labeltodos"> Todos <input type="checkbox" id="checktodos" /> </label> 
								</fieldset>
							</form> 								
						</a>
					</li>
				</ul>
				
            </div>
			

			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Destino" data-icon="check"> <a href="#"> Destino </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
			<div data-role="popup" id="permutarDestino" style="min-width:250px;" >
				<div class="custom-corners ui-corner-all">
					<div class="ui-bar ui-bar-a" style="text-align:center;background-color:#4887cc;color:#ffffff;text-shadow:none;">
						<h3>Destino</h3>
					</div>
					<div class="ui-body ui-body-a">
						<form>
							<label for="mes_destino" ></label>
							<select name="mes_destino" id="mes_destino"></select>
							
							
							<input type="button" id="ok_Permutar" value="Ok" style="text-shadow:none;">	  
						</form>
					</div>
				</div>
			</div>
			
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			<script>
				$("#permutar_itens").on("pageshow", function()
					{
				
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
					
						$("#permutar_itens #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#permutar_itens #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_mov_permutar = {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql : "permutar_mesas_itens" , par1: f_storageready("mes_codigo")  };
						$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', json_mov_permutar, function(data) 
							{
								json_mov_permutar = data;
								$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', { action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql :"mesas_destino" , par1: f_storageready("mes_codigo") }, function(data) 
									{
										f_select("#mes_destino", data,  "mes_codigo", "mes_descricao", data[0]["mes_codigo"] );
										$("#mes_destino").selectmenu("refresh");
										
										$("#permutar_itens #list_PermutarItens li:not(.linhatodos)").remove();
										$("#permutar_itens #title ").html( f_storageready('mes_descricao'));
										
										var list_PermutarItens = "";
										for(var i = 0, len = json_mov_permutar.length; i < len; i++) 
											{						
												var vst = json_mov_permutar[i]["mov_print"] == "S" ? "checked" : "";
												var pdz = json_mov_permutar[i]["mov_produzido"] == "S" ? "checked" : "";
										
												
												list_PermutarItens += "<li>" +
																	"<a href='#' class='ui-btn' style='padding:0;'>" +
																		"<div>" + 
																			"<label class='custom-label'>"+json_mov_permutar[i]["mov_qtde"]+ " - " + json_mov_permutar[i]["pro_descricao"] + "<input type='checkbox' /></label>" + 
																		"</div>" +
																		"<p style='margin-left:.6em;font-size:.95em;'>Observação : "+json_mov_permutar[i]["mov_observacao"]+"</p>" + 
																	"</a>" +
																	"<div class='right_check'>" + 
																		"<label style='border:none;padding-top: 0;padding-bottom: 0;'>C<input type='checkbox' disabled "+vst+" /></label>" +
																		"<label style='border:none;padding-top: 0;padding-bottom: 0;'>P<input type='checkbox' disabled "+pdz+" /></label>" +   
																	"</div>" +
																"</li>";
												
											}
										$(":checkbox").checkboxradio();	
										$("#list_PermutarItens").append(list_PermutarItens).trigger("create");	
										$("#list_Title").listview('refresh');	

									});
							});
						
													
						
						$('#list_PermutarItens').on('change', 'input[type=checkbox]', function () 
							{ 
								if( $(this).attr('id') == "checktodos") 
									{ 
										setTimeout( function() 
											{  
												$("#list_PermutarItens #checktodos").prop("checked") == true ? $("#list_PermutarItens a :checkbox").prop("checked", true).checkboxradio("refresh") :  $("#list_PermutarItens a :checkbox").prop("checked", false).checkboxradio("refresh");	
											}, 100);
									}
							});
						
						$("#permutar_itens #menuopcoes").on("click", "li", function()	 
							{
								if($(this).attr("id") == "Destino") 
									{
										$("#permutar_itens #menuopcoes").popup("close");
										setTimeout(function () 
											{
												$("#permutar_itens #permutarDestino").popup("open");
											}, 100);
											
										return false;				
									}		
							});		
						
						
						$("#ok_Permutar").on("click", function()
							{
								$.mobile.loading("show");										
								$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=??', {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],sql :"garcon_destino", par1: ElementsAspa($("#mes_destino").val()) }, function(data) 
									{
										var wgarcon = "";
										if( JSON.stringify(data).indexOf("sql_record_null") >= 0 )
											{  
												wgarcon = JSON.parse(f_storageready("json_usu"))[0]["usu_nome"];	
											}
										else 
											{
												wgarcon = data[0]["usu_nome"];
											}
										var wsql = "";
										var wdele = "";		
										$.each($("#list_PermutarItens a input[type=checkbox]:not(#checktodos)"), function(indice, value)
											{
												if($(this).is(":checked") == true)
													{ 
														wsql +=  wdele +  					
																ElementsAspa(json_mov_permutar[indice]["pro_codigo"]) + "," +		
																ElementsAspa(json_mov_permutar[indice]["mov_qtde"]) + "," + 											
																json_mov_permutar[indice]["mov_valorunitario"] + "," +						
																ElementsAspa(json_mov_permutar[indice]["mov_datahoravenda"]) + "," +
																ElementsAspa(json_mov_permutar[indice]["mov_datahoradespacho"]) + "," +														
																ElementsAspa(json_mov_permutar[indice]["mov_status"]) + "," +								
																ElementsAspa(json_mov_permutar[indice]["mov_print"]) +	"," +
																ElementsAspa(json_mov_permutar[indice]["mov_produzido"]) + "," +
																ElementsAspa(json_mov_permutar[indice]["mov_observacao"]);		
																												
																wdele = "©";
																wsql += wdele +  " mes_codigo = " + f_storageready("mes_codigo") +
																				 " and mov_codigo = " + json_mov_permutar[indice]["mov_codigo"];	
													}
											});
										if(wsql == "") 
											{
												f_storagewrite("erro_mensagem" , "Favor checar iten(s), para permutar...." );
												f_storagewrite("page_redirect" , "permutar_itens.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}	
										$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action: "f_permutarpedidos",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],mes : $("#mes_destino").val(),usu : ElementsAspa(wgarcon),sql: wsql }, function(data) 
											{
												$ .mobile.changePage ("menu_principal.php");		
											});
									});	   	
							});	
					});
			
			</script>
			
			
			
        </div>
		
		
</body>
</html>