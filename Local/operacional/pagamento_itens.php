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

        <div data-role="page" id="pagamento_itens">  
           
            <div data-role="header">
			
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
				<h1>Pagamento</h1>
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
			
				<ul data-role="listview" data-icon="false" id="list_PagamentoItens">
					<li style='padding:0;background-color:#dddddd;' class="linhatodos">
						<a href="#"  style="padding:0;background-color:#dddddd;border-color:#dddddd;">
							<form> 
								<fieldset data-role="controlgroup" style="display:inline;border:none;"> 
									<label  style="border:none;background-color:#dddddd;" id="labeltodos"> Todos <input type="checkbox" id="checktodos" checked /> </label> 
								</fieldset>
							</form> 								
						</a>
					</li>
				</ul>
				
            </div>
           
		   
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true">
					<li  data-role="list-divider"> Opções </li>
					<li id="Gravar" data-icon="check"> <a href="#">Pagar</a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php">Voltar</a> </li>
				</ul>
			</div>
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			
			<script>
				$("#pagamento_itens").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
								{
									location.href="notfound.php";
									return true;	
								}		 
						
						$("#pagamento_itens #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#pagamento_itens #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql : "pagamento_mesas_itens", par1: f_storageready("mes_codigo")}, function(data) 
							{
								//$("#pagamento_itens #list_PagamentoItens li:not(.linhatodos)").remove();
								$("#pagamento_itens #title ").html( f_storageready("mes_descricao") + "<span class='ui-li-count' id='totalGeral' style='display:inline;' ></span>");
								
								var list_PagamentoItens = "";
								for(var i = 0, len = data.length; i < len; i++) 
									{						
										list_PagamentoItens += "<li><a href='#' class='ui-btn' style='padding:0;'>" +
																	"<div>" + 
																		"<label id='"+data[i]["mov_codigo"]+"' class='custom-label'>"+data[i]["mov_qtde"]+ " - " + data[i]["pro_descricao"] + "<input type='checkbox' checked /></label>" + 
																	"</div>" +
																	"<p style='margin-left:.6em;font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+"</p>" + 
																	"<span class='ui-li-count' style='font-weight:700;'>" +
																		data[i]["mov_total"]  +
																	"</span>" +
																"</a></li>";
									}
								$(":checkbox").checkboxradio();	
								$("#list_PagamentoItens").append(list_PagamentoItens).trigger("create").listview("refresh");	
								soma("#list_PagamentoItens input[type=checkbox]:not(#checktodos)", true, "#pagamento_itens #totalGeral");
								$("#list_Title").listview('refresh');	
								
							});					
						
						
						
													
												
						
						$('#list_PagamentoItens').on('change', 'input[type=checkbox]', function () 
							{ 
								if( $(this).attr('id') == "checktodos") 
									{ 
										setTimeout( function() 
											{  
												$("#list_PagamentoItens #checktodos").prop("checked") == true ? $("#list_PagamentoItens :checkbox").prop("checked", true).checkboxradio("refresh") :  $("#list_PagamentoItens :checkbox").prop("checked", false).checkboxradio("refresh");	
											}, 100 );
								
									}
									
								setTimeout( function() 
									{  
										
										soma("#list_PagamentoItens input[type=checkbox]:not(#checktodos)", true, "#pagamento_itens #totalGeral");
									}, 100 );	
								
								
							});
						
						
						$("#pagamento_itens #menuopcoes").on("click", "li", function()	 
							{
								if($(this).attr("id") == "Gravar") 
									{
										$("#menuopcoes ul li a ").removeClass("ui-btn-active");
										$(this).find("a").addClass("ui-btn-active");
							
										var wseparador = "";
										var wmov_codigo = "";	
										$.each($("#list_PagamentoItens input[type=checkbox]:not(#checktodos)"), function(indice, value)
											{
												if($(this).is(":checked") == true)
													{ 
														wmov_codigo += wseparador + $(this).siblings("label").attr("id");
														wseparador = "©";				
													}
											});
										if(wmov_codigo == "") 
											{
												f_storagewrite("erro_mensagem" , "Favor checar iten(s), para pagamento...." );
												f_storagewrite("page_redirect" , "pagamento_itens.php" );
												$ .mobile.changePage ("error.php");
												return true;														
											}
											
										f_storagewrite("pagamento_subtotal" , $("#pagamento_itens #totalGeral").text().replace("R$", "") );
										f_storagewrite("pagamento_movcodigo" , wmov_codigo );
										$ .mobile.changePage ("pagamento_total.php");
									}		
							});
			
						
					});
			</script>
			
        </div>
		
		
</body>
</html>