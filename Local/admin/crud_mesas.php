<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_mesas">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Mesas</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
            <div data-role="main" class="ui-content">
				
				<div class="ui-corner-all custom-corners">
					<div class="ui-bar ui-bar-a" style="background-color:#4887cc;color:#ffffff;text-shadow:none;">
						<h3 id="title_Formulario">...</h3>
					</div>
					<div class="ui-body ui-body-a">
						
						<form method="post" >
							
							<label for="mes_codigo">Código : [0-9] </label>
							<input type="number" name="mes_codigo" id="mes_codigo" disabled>
							
							<label for="mes_descricao">Descrição: </label>
							<input type="text" id="mes_descricao" name="mes_descricao" />
						   
							<label for="mes_ativa">Ativa : </label>
							<select id="mes_ativa" name="mes_ativa">
								<option value="S">Sim</option>
								<option value="N">Não</option>
							</select>
							  
						</form>
						
					</div>
				</div>	

            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="mesas.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			
			<script>
				$("#crud_mesas").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#crud_mesas #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_mesas #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$("#title_Formulario").text(f_storageready("sql_statments"));
						f_ElementsProperties("#mes_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_mesclick"))[0]["mes_codigo"] );
						f_ElementsProperties("#mes_descricao"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_mesclick"))[0]["mes_descricao"] );
						f_ElementsProperties("#mes_ativa"	,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_mesclick"))[0]["mes_ativa"] );
						
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#mes_descricao").textinput("enable") : $("#mes_descricao").textinput("disable"); 
						f_ElementsProperties("#mes_ativa"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
						
						$("#mes_descricao").focus(); 
						$("select").selectmenu("refresh");
						
						ElementsValidation( [ {"Id" : "mes_descricao", "Caracteres" : "30"}], "maxlength" );
				
						$("#crud_mesas #menuopcoes ul").on( "click", "li", function() 
							{
								if($(this).attr('id') == "Voltar") 
									{
										if( f_storageready("json_mes").indexOf("null") >= 0  )
											{  
												$(this).find("a").attr("href", "menu_principal.php");
												$("#crud_mesas #menuopcoes ul").hasClass('ui-listview') ? $("#crud_mesas #menuopcoes ul").listview('refresh') : $("#crud_mesas #menuopcoes ul ").trigger('create');	
												return true;						
											}	
									}
								if($(this).attr('id') == "Gravar") 
									{
										$.mobile.loading("show");	
										if(ElementsValidation([ {"Id" : "mes_descricao", "Caracteres" : "1"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_mesas.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										
										
										var wsql = f_storageready("sql_statments") == "Incluir" ? "insert into mes (mes_descricao, mes_ativa) values ("+ 
																									 ElementsAspa($("#mes_descricao").val()) + "," +
																									 ElementsAspa($("#mes_ativa").val()) + ")" : "" ; 
						
										wsql = f_storageready("sql_statments") == "Alterar" ? "update mes set mes_descricao = " + ElementsAspa($("#mes_descricao").val()) + "," +
																					"mes_ativa = " + ElementsAspa($("#mes_ativa").val()) + 
																					" where mes_codigo =  " + ElementsAspa($("#mes_codigo").val()) : wsql;
																					
										wsql = f_storageready("sql_statments") == "Excluir" ? "delete from mes where mes_codigo = " + ElementsAspa($("#mes_codigo").val()) + 
																								" and not mes_codigo in (select mov.mes_codigo from mov mov) " : wsql;
									
										
										var php_wret = {action: "f_multquery", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql};
										$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', php_wret, function(data)
											{
												if(f_storageready("sql_statments") == "Excluir")
													{
														if(data.rows <= 0) 
															{
																f_storagewrite("erro_mensagem" , "Transação não concluída, Mesa com movimentação..." );
																f_storagewrite("page_redirect" , "mesas.php" );
																$ .mobile.changePage ("error.php");
																return true;
															}
													}
												$ .mobile.changePage ("mesas.php");
											});
									}
							});
						
						$('#mes_codigo, #mes_descricao, #mes_ativa').keypress(function(e)
							{
								if ( e.keyCode == 13 )
									{
										return false;
									} 
							}); 
					});
			</script>
			
        </div>
		
</body>
</html>