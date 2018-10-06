<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
    </head>
    <body>

        <div data-role="page" id="setores">  
           
            <div data-role="header" data-position="fixed">
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Setores</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
            <div data-role="main" class="ui-content" >
			
				<ul data-role="listview" data-icon="false" id="list_Setores">
					<li style='padding:0;background-color:#dddddd;' class="linhatodos">		
						<a href="#" style="padding:0;background-color:#dddddd;border-color:#dddddd;">							
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
					<li data-role="list-divider"> Opções </li>
					<li id="Avançar" data-icon="forward"> <a href="#"> Avançar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			
			<script>
				$("#setores").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
					
						$("#setores #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#setores #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_sto = "";
						if(f_storagecheck("json_sto") == true) 
							{
								json_sto = JSON.parse(f_storageready("json_sto"));
								f_storagewrite("json_sto", JSON.stringify(json_sto));	
									
							}
						else 
							{
								json_sto = {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],	sql: "sto"};
								$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', json_sto, function(data) 
									{
										if( JSON.stringify(data).indexOf("sql_record_null") >= 0 )
											{  
												f_storagewrite("erro_mensagem" , "Setores não cadastrado(s)..." );
												f_storagewrite("page_redirect" , "menu_principal.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										json_sto = data;
										f_storagewrite("json_sto", JSON.stringify(data));	
									});
							}
						var request_populationList = setInterval(function()
														{
															if(f_storagecheck("json_sto") == true) 
																{
																	$("#list_Setores li:not(.linhatodos)").remove();
																	var list_Setores = "";
																	for(var i = 0, len = json_sto.length; i < len; i++) 
																		{	
																			list_Setores += "<li><a href='#' class='ui-btn' style='padding:0;'>" +
																								"<div>" + 
																									"<label style='border:none;width:225px;text-overflow:ellipsis;overflow:hidden;white-space: nowrap;'>"+json_sto[i]["sto_descricao"]+ "<input type='checkbox' /></label>" + 
																								"</div>" +
																							"</a></li>";
																			
																		}
																	$(":checkbox").checkboxradio();	
																	$("#list_Setores").append(list_Setores).trigger("create");
																	
																	clearInterval(request_populationList);	
																} 															
														}, 100);	
						
						$('#list_Setores').on('change', 'input[type=checkbox]', function () 
							{ 
								if( $(this).attr('id') == "checktodos") 
									{ 
										setTimeout( function() 
											{  
												$("#list_Setores #checktodos").prop("checked") == true ? $("#list_Setores :checkbox").prop("checked", true).checkboxradio("refresh") :  $("#list_Setores :checkbox").prop("checked", false).checkboxradio("refresh");	
											}, 100);
									}
							});
						
						$("#menuopcoes ul").on( "click", "li", function() 
							{
								$("#menuopcoes ul li a ").removeClass("ui-btn-active");
								$(this).find("a").addClass("ui-btn-active");
								
								if($(this).attr('id') == "Avançar") 
									{
										var wseparador = "";
										var wsto_codigo = "";
										$.each($("#list_Setores input[type=checkbox]:not(#checktodos)"), function(i, v)
											{
												if($(this).prop("checked") == true)	
													{
														wsto_codigo += wseparador + ElementsAspa(json_sto[i]["sto_codigo"]);
														wseparador = ",";	
													}
											
											});
											
										if(wsto_codigo == "") 
											{
												f_storagewrite("erro_mensagem" , "Favor checar itens..." );
												f_storagewrite("page_redirect" , "setores.php" );
												$ .mobile.changePage ("error.php");
												
												
											}
										else 
											{
												f_storagewrite("json_messto", wsto_codigo);
												$ .mobile.changePage ("mesas_setor.php");
	
											}		
									}
										
							});
					
							
					});
			</script>
			
        </div>
    </body>
</html>