<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
        
    </head>
  
   
    <body>
       
        <div data-role="page" id="comandar_itens">  
           
            <div data-role="header">
			
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
				<h1>Comandar</h1>
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
				
				
				<div id="mes_null">&lt;Vazio&gt;</div>
				
					
				<ul data-role="listview" data-icon="false" id="list_ComandarItens">
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
					
					<li id="Avançar" data-icon="check"> <a href="#"> Avançar  </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			<script>
				$("#comandar_itens").on("pageshow", function()
					{					
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
					
						$("#comandar_itens #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#comandar_itens #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_pro = {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql : "comandar_mesas_itens", par1 : f_storageready("mes_codigo") };
						$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', json_pro, function(data) 
							{
								if(JSON.stringify(data).indexOf("sql_record_null") >= 0) 
									{
										$("#list_ComandarItens, #list_Title").css("display", "none");
										$("#mes_null").css("display", "block");
										return false;
									}
								else 
									{
										json_pro = data;
										//$("#comandar_itens #list_ComandarItens li:not(.linhatodos)").remove();
										$("#comandar_itens #title ").html( f_storageready('mes_descricao') );
									
										var list_ComandarItens = "";
										for(var i = 0, len = data.length; i < len; i++) 
											{						
												var vst = data[i]["mov_print"] == "S" ? "checked" : "";
												var pdz = data[i]["mov_produzido"] == "S" ? "checked" : "";
												
												list_ComandarItens += "<li>" +
																			"<a href='#' class='ui-btn' style='padding:0;'>" +
																				"<div>" + 
																					"<label style='border:none;width:210px;text-overflow:ellipsis;overflow:hidden;white-space: nowrap;'>"+data[i]["mov_qtde"]+ " - " + data[i]["pro_descricao"] + "<input type='checkbox' /></label>" + 
																				"</div>" +
																				"<p style='margin-left:.6em;font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+"</p>" + 
																			"</a>" +
																			"<div class='right_check'>" + 
																				"<label style='border:none;padding-top: 0;padding-bottom: 0;'>C<input type='checkbox' disabled "+vst+" /></label>" +
																				"<label style='border:none;padding-top: 0;padding-bottom: 0;'>P<input type='checkbox' disabled "+pdz+" /></label>" +   
																			"</div>" +
																		"</li>";
											}
										$(":checkbox").checkboxradio();	
										$("#list_ComandarItens").append(list_ComandarItens).trigger('create');	
										$("#list_Title").listview('refresh');	
										
									}	
								
							});					
						
						$('#list_ComandarItens').on('change', 'input[type=checkbox]', function () 
							{ 
								if( $(this).attr('id') == "checktodos") 
									{ 
										setTimeout( function() 
											{  
												$("#list_ComandarItens #checktodos").prop("checked") == true ? $("#list_ComandarItens a :checkbox").prop("checked", true).checkboxradio("refresh") :  $("#list_ComandarItens a :checkbox").prop("checked", false).checkboxradio("refresh");	
											}, 100 );
									}
								
								
							});
						
						
						$("#comandar_itens #menuopcoes").on("click", "li", function()	 
							{
								if($(this).attr('id') == "Avançar")
									{
											
										if($("#list_ComandarItens a :checkbox").is(":checked") == false) 
											{
												f_storagewrite("erro_mensagem" , "favor, checar itens..." );
												f_storagewrite("page_redirect" , "comandar_itens.php" );
												$ .mobile.changePage ("error.php");
												return true;	
											}
											
										var wpro_array = [{}];
										$.each($("#list_ComandarItens a input[type=checkbox]:not(#checktodos)"), function(i,v)
											{
												if($(this).is(":checked") == true) 
													{
														json_pro[i]["mov_comanda"] = "S";
														wpro_array.push(  json_pro[i] )
													}
													
											});
										
										$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action: "f_datahora"}, function(data) 
											{
												wpro_array.shift();
												wpro_array.sort(sort_by('sto_codigo', false, 0));
												
												f_storagewrite("json_dados", JSON.stringify(wpro_array) );
												f_storagewrite("rel", "cda");
												f_storagewrite("interaction", "SS action108");
												f_storagewrite("fil", "* " + f_storageready("mes_descricao").toUpperCase() + " * " + data.DataHora + " " );
												
												$ .mobile.changePage ("menu_impressoras.php");		
											});
										
									
									}
							});
						
					});
			</script>
			
			
			
        </div>
		
		
</body>
</html>