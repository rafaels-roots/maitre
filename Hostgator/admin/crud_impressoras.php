<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_impressoras">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Impressoras</h1>
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
							
							<label for="imp_codigo">Código : [0-9] </label>
							<input type="number" name="imp_codigo" id="imp_codigo" data-wrapper-class="ui-custom" disabled>
							
							<label for="imp_ip">IP :  </label>
							<input type="text" name="imp_ip" id="imp_ip" data-wrapper-class="ui-custom" >
							
							<label for="imp_porta">Porta :  </label>
							<input type="number" name="imp_porta" id="imp_porta" maxlength="4" data-wrapper-class="ui-custom">
							
							<label for="imp_local">Local : </label>
							<input type="text" id="imp_local" name="imp_local" maxlength="30" data-wrapper-class="ui-custom" />
							 
							<label for="imp_ativa">Ativa : </label>
							<select id="imp_ativa" name="imp_ativa">
								<option value="S">Sim</option>
								<option value="N">Não</option>
							</select>
							 
							<label for="imp_avancorodape">Avanço rodapé : [0-9] </label>
							<input type="number" id="imp_avancorodape" name="imp_avancorodape" maxlength="3" data-wrapper-class="ui-custom" />
							 
							
							 
						</form>
						
					</div>
				</div>	

            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider" > Opções </li>
					
					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="impressoras.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
				$("#crud_impressoras").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						
						$("#crud_impressoras #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_impressoras #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$("#title_Formulario").text(f_storageready("sql_statments"));
						f_ElementsProperties("#imp_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_impclick"))[0]["imp_codigo"] );
						f_ElementsProperties("#imp_ip"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_impclick"))[0]["imp_ip"] );
						f_ElementsProperties("#imp_porta"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_impclick"))[0]["imp_porta"] );
						f_ElementsProperties("#imp_local"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_impclick"))[0]["imp_local"] );
						f_ElementsProperties("#imp_ativa"	,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_impclick"))[0]["imp_ativa"] );
						f_ElementsProperties("#imp_avancorodape"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_impclick"))[0]["imp_avancorodape"] );
							
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#imp_ip").textinput("enable") : $("#imp_ip").textinput("disable"); 
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#imp_porta").textinput("enable") : $("#imp_porta").textinput("disable"); 
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#imp_local").textinput("enable") : $("#imp_local").textinput("disable"); 
						f_ElementsProperties("#imp_ativa"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#imp_avancorodape").textinput("enable") : $("#imp_avancorodape").textinput("disable"); 
						
						$("#imp_ip").focus();
						$("select").selectmenu("refresh");
						
						$("#crud_impressoras #menuopcoes ul").on( "click", "li", function() 
							{		
								if($(this).attr('id') == "Voltar") 
									{
										if( f_storageready("json_imp").indexOf("null") >= 0  )
											{  
												$(this).find("a").attr("href", "menu_principal.php");
												$("#crud_impressoras #menuopcoes ul").hasClass('ui-listview') ? $("#crud_impressoras #menuopcoes ul").listview('refresh') : $("#crud_impressoras #menuopcoes ul ").trigger('create');	
												return true;						
											}	
									}

								if($(this).attr('id') == "Gravar") 
									{
										$.mobile.loading("show");
										if(ElementsValidation([ {"Id" : "imp_ip", "Caracteres" : "1"}, {"Id" : "imp_porta", "Caracteres" : "1"},{"Id" : "imp_local", "Caracteres" : "1"}, {"Id" : "imp_avancorodape", "Caracteres" : "1"} ], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_impressoras.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
									
										var wsql = f_storageready("sql_statments") == "Incluir" ? $("#imp_ip").val() + "®" +
																								  $("#imp_porta").val() + "®" +
																								  $("#imp_local").val() + "®" +
																								  $("#imp_ativa").val() + "®" + 
																								  $("#imp_avancorodape").val() + "©" : "" ; 
																		
										wsql = f_storageready("sql_statments") == "Alterar" ? $("#imp_ip").val() + "®" +
																							  $("#imp_porta").val() + "®" +
																							  $("#imp_local").val() + "®" +
																							  $("#imp_ativa").val() + "®" +
																							  $("#imp_avancorodape").val() + "®" +																					
																							  $("#imp_codigo").val() + "©" : wsql;
																					
										wsql = f_storageready("sql_statments") == "Excluir" ? $("#imp_codigo").val() + "©" : wsql;

										var waction = f_storageready("sql_statments") == "Incluir" ? "f_insert" : f_storageready("sql_statments") == "Alterar" ? "f_update" : "f_delete";

										$.getJSON('http://www.sisleq.com.br/maitre/admin/imp_processa.php?callback=?', {action: waction, banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql}, function(data)
											{
												$ .mobile.changePage ("impressoras.php");
											});
									}
							});
						
						$("#imp_avancorodape").keypress(function(e)
							{
								if( /[0123456789]/.test(String.fromCharCode(e.keyCode)) ) 
									{ 	
										if($(this).val().length > 2) 
											{
												return false;	
											}
										return true;	
									}
								return false;
							});
							
					
						
						$("#imp_porta").keypress(function(e)
							{
								if( /[0123456789]/.test(String.fromCharCode(e.keyCode)) ) 
									{ 	
										if($(this).val().length > 3) 
											{
												return false;	
											}
										return true;	
									}
								return false;
							});
							
						$('#imp_porta, #imp_local, #imp_ativa, #imp_avancorodape, #imp_ip').keypress(function(e)
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