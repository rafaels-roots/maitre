<!DOCTYPE html>
<html>
    <head>  
		<title>Maitre | Operacional</title>  
		<meta charset="utf-8">	
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
	</head>    
	      
	<body>      
	
		<div data-role="page" id="despachar_itens">
		
			<div data-role="header">	
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a> 

				<h1>Despachar</h1>	
				
				<div data-role="navbar">	
					<ul>				
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>		
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 	
					</ul>	
				</div>		
			
				<ul data-role="listview" id="list_Title" >
					<li  data-role="list-divider" id="title"></li>
				</ul>
			</div>             			 


			<div data-role="main" class="ui-content">	
				<ul data-role="listview" data-icon="false" id="list_DespacharItens"  >	
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
					<li  data-role="list-divider" > Opções </li>
					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a> </li>			
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>	
				</ul>			
			</div>					

			<div data-role="footer" data-position="fixed">     
				<h1>Maitre | Operacional</h1>       
			</div>			        
			
			<script>
				$("#despachar_itens").on("pageshow", function()
					{
						if(f_storagecheck("mob") == false) 
								{
									location.href="notfound.php";
									return true;	
								}		 
					
						$("#despachar_itens #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#despachar_itens #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql : "despachar_mesas_itens", par1 : f_storageready("mes_codigo") }, function(data) 
							{
								//$("#despachar_itens #list_DespacharItens li:not(.linhatodos)").remove();
								$("#despachar_itens #title ").html( f_storageready('mes_descricao'));
								
								var list_DespacharItens = "";
								for(var i = 0, len = data.length; i < len; i++) 
									{						
										var vst = data[i]["mov_print"] == "S" ? "checked" : "";
										var pdz = data[i]["mov_produzido"] == "S" ? "checked" : "";
								
										list_DespacharItens += "<li>" +
															"<a href='#' class='ui-btn' style='padding:0;'>" +
																"<div>" + 
																	"<label class='custom-label' id='"+data[i]["mov_codigo"]+"'>"+data[i]["mov_qtde"]+ " - " + data[i]["pro_descricao"] + "<input type='checkbox' /></label>" + 
																"</div>" +
																"<p style='margin-left:.6em;font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+"</p>" + 
															"</a>" +
															"<div class='right_check'>" + 
																"<label style='border:none; padding-top: 0;padding-bottom: 0;'>C<input type='checkbox' disabled "+vst+" /></label>" +
																"<label style='border:none; padding-top: 0;padding-bottom: 0;'>P<input type='checkbox' disabled "+pdz+" /></label>" +   
															"</div>" +
														"</li>";
									}
								$(":checkbox").checkboxradio();	
								$("#list_DespacharItens").append(list_DespacharItens).trigger('create');	
								$("#list_Title").listview('refresh');	
									
							});							
											
						
						
						$('#list_DespacharItens').on('change', 'input[type=checkbox]', function () 
							{ 
								if( $(this).attr('id') == "checktodos") 
									{ 
										setTimeout( function() 
											{  
												$("#list_DespacharItens #checktodos").prop("checked") == true ? $("#list_DespacharItens a :checkbox").prop("checked", true).checkboxradio("refresh") :  $("#list_DespacharItens a :checkbox").prop("checked", false).checkboxradio("refresh");	
											}, 100);
									}
							});
						
						
						$("#despachar_itens #menuopcoes").on("click", "li", function()	 
							{
								
								if($(this).attr("id") == "Gravar") 
									{	
										$("#menuopcoes li a ").removeClass("ui-btn-active");
										$(this).find("a").addClass("ui-btn-active");
										$.mobile.loading("show");										

										var wsql = "";
										var wdele = "";	
										$.each($("#list_DespacharItens a input[type=checkbox]:not(#checktodos)"), function(indice, value)
											{
												if($(this).is(":checked") == true) 
													{
														
														wsql +=  wdele +
														f_storageready("mes_codigo") + "|" +
														$(this).siblings("label").attr("id"); 			  
														wdele = "©";
													}
											});
										if(wsql == "") 
											{
												f_storagewrite("erro_mensagem" , "Favor checar iten(s), para despachar...." );
												f_storagewrite("page_redirect" , "despachar_itens.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}	
										$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action: "f_despacharpedidos", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql: wsql }, function(data) 
											{
												$ .mobile.changePage ("menu_principal.php");	   	
											});
									}		
							});	
					});
			</script>
			
			
			
		</div>				
		
	
	</body>

</html>