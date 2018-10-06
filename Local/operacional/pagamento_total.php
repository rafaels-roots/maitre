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

        <div data-role="page" id="pagamento_total">  
           
            <div data-role="header">
			
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
				<h1>Pagamento</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
				
            </div> 
            
			 
			
            <div data-role="main" class="ui-content">
					
				<div class="ui-corner-all custom-corners">
					<div class="ui-bar ui-bar-a" style="background-color: #4887cc;color:#ffffff;text-shadow:none;">
						<h3 id="title_Formulario">Total</h3>
					</div>
					<div class="ui-body ui-body-a">
						
						<form method="post" >
						
							<label for="mov_subtotal">Subtotal  </label>
							<input type="text" class="mov_subtotal" id="mov_subtotal" disabled />
									
							<label for="par_garconcomissao">Comissão  </label>
							<input type="text" class="par_garconcomissao" id="par_garconcomissao" disabled />
							
							<label for="mov_total">Total  </label>
							<input type="text" class="mov_total" id="mov_total" style="font-weight:bold;" disabled />
							
							<label for="mov_valorrecebido">Valor Recebido  </label>
							<input type="text" class="mov_valorrecebido" id="mov_valorrecebido" />  
						
						
							<label for="mov_valortroco">Troco  </label>
							<input type="text" class="mov_valortroco" id="mov_valortroco" style="font-weight:bold;" disabled />  
							
							<h3 style="border-bottom:1px solid #ddd;padding-bottom:6px;">Distribuir Pagamento</h3>
							
							<fieldset></fieldset>
							
							
							<input type="button" id="ok_Pagamento" value="Ok" style="text-shadow:none;"/>
						
						</form>
						
					</div>
				</div>	
					
            </div>
           
		   
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true">
					<li  data-role="list-divider"> Opções </li>
					<li id="Voltar" data-icon="back"> <a href="pagamento_itens.php">Voltar</a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			
			<script>
				$("#pagamento_total").on("pageshow", function() 
					{
				
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
							
						$("#pagamento_total #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#pagamento_total #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						var cod_fpg = "";
						
						$("#title_Formulario").html(f_storageready("mes_descricao") );
						
						$("#mov_subtotal").val(f_storageready("pagamento_subtotal"));
						var par_garconcomissao = parseFloat(f_storageready("pagamento_subtotal")) * parseFloat(JSON.parse(f_storageready("json_par"))[0]["par_garconcomissao"]); 
						$("#par_garconcomissao").val( par_garconcomissao.toFixed(2) );
						
						var mov_total = parseFloat(f_storageready("pagamento_subtotal")) + (parseFloat(f_storageready("pagamento_subtotal")) * parseFloat(JSON.parse(f_storageready("json_par"))[0]["par_garconcomissao"]));
						$("#mov_total").val(  mov_total.toFixed(2)  );
						
						var json_fpg = {action: "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],sql: "fpg"};
						if(f_storagecheck("json_fpg") == true) 
							{
								json_fpg = JSON.parse(f_storageready("json_fpg"));
								f_storagewrite("json_fpg", JSON.stringify(json_fpg) );
							}
						else 
							{
								$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', json_fpg, function(data) 
									{	
										json_fpg = data;
										f_storagewrite("json_fpg", JSON.stringify(data));
									});	
							}
						
						
						var request_populationList = setInterval(function()
														{
															if(f_storagecheck("json_fpg") == true) 
																{
																	var fpg_inputs = "";
																	for(var i = 0, len = json_fpg.length; i < len; i++) 
																		{
																			fpg_inputs += "<label for='fpg"+i+"'> "+json_fpg[i]["fpg_descricao"]+"</label> <input type='number' class='fpg_inputs' id='fpg"+i+"'> "; 
																				
																		}
																	$("form fieldset").append(fpg_inputs);
																	$("input[type=number]").textinput();
																	clearInterval(request_populationList);	
																}
														}, 100);
						
						
						
					
						$("#ok_Pagamento").on("click", function()
							{														
								if($("#mov_valortroco").val().replace("R$", "") < 0) 
									{
										f_storagewrite("erro_mensagem" , "´Valor recebido menor..." );
										f_storagewrite("page_redirect" , "pagamento_total.php" );
										$ .mobile.changePage ("error.php");
										return true;	
									}
									
								var wsql = "";
								var wdele = "";	
								var InputsValue = 0;								
								$.each( $(".fpg_inputs") , function(i,v)
									{
										if( $(this).val() != "" ) 
											{
												InputsValue += parseFloat($(this).val())
												wsql +=  wdele + ElementsAspa(json_fpg[i]["fpg_codigo"]) + "," + $(this).val();
												wdele = "©";	
											}	
												
									});
								if(InputsValue == $("#mov_total").val()) 
									{
										$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action: "f_pagamento",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],mes : f_storageready("mes_codigo"),usu : JSON.parse( f_storageready("json_usu") )[0]["usu_nome"],cod : f_storageready("pagamento_movcodigo"),sql: wsql }, function(data) 
											{	
												$ .mobile.changePage ("pagamento_mesas.php");	
											});
									}
								else 
									{		
										f_storagewrite("erro_mensagem" , "A distribução difere do valor total ..." );
										f_storagewrite("page_redirect" , "pagamento_total.php" );
										$ .mobile.changePage ("error.php");
										return true;
									}
									
							});
						
						$(".fpg_inputs , #mov_valorrecebido").on("keypress", function(e)
							{
								if( /[0123456789.]/.test(String.fromCharCode(e.keyCode)) ) 
									{ 
										return true;
									}
									return false;
							});
						
						$("#mov_valorrecebido").on("keyup" , function()
							{	
								if($(this).val() != "") 
									{
										var valueTotal = parseFloat($(this).val()) - parseFloat($("#mov_total").val());
										$("#mov_valortroco").val(valueTotal.toFixed(2));				
									}
								else 
									{
										$("#mov_valortroco").val("");													
									}	
							});		
					
						
						
					});
			</script>
			
        </div>
		
		
</body>
</html>