<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_usuarios">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Usuários</h1>
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
							
							<label for="usu_nome">Nome : </label>
							<input type="text" name="usu_nome" id="usu_nome" maxlength="30" data-wrapper-class="ui-custom">
							
							<label for="usu_senha">Senha : </label>
							<input type="password" id="usu_senha" name="usu_senha"  maxlength="15" data-wrapper-class="ui-custom" />
														
							<label for="usu_csenha">Confirme sua senha : </label>
							<input type="password" id="usu_csenha" name="usu_csenha"  maxlength="15"  data-wrapper-class="ui-custom"/>

							<label for="usu_modulos">Módulos de Acesso : [Pedidos/Chef/Caixa/Admin] </label>
							<input type="text" id="usu_modulos" name="usu_modulos" maxlength="30" data-wrapper-class="ui-custom" />

							<label for="usu_ativo">Ativo : </label>
							<select id="usu_ativo" name="usu_ativo">
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
					<li id="Voltar" data-icon="back"> <a href="usuarios.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			
			<script>
				$("#crud_usuarios").on("pageshow", function() 
					{
						
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#crud_usuarios #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_usuarios #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$("#title_Formulario").text(f_storageready("sql_statments"));
						f_ElementsProperties("#usu_nome"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_usuclick"))[0]["usu_nome"] );
						f_ElementsProperties("#usu_senha"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_usuclick"))[0]["usu_senha"]  );
						f_ElementsProperties("#usu_csenha"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_usuclick"))[0]["usu_senha"]  );
						f_ElementsProperties("#usu_modulos"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_usuclick"))[0]["usu_modulos"]  );
						f_ElementsProperties("#usu_ativo"	,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_usuclick"))[0]["usu_ativo"]  );
						
						f_storageready("sql_statments") == "Excluir" || f_storageready("sql_statments") == "Alterar" ? $("#usu_nome").textinput("disable") : $("#usu_nome").textinput("enable"); 
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#usu_senha").textinput("enable") : $("#usu_senha").textinput("disable"); 
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#usu_csenha").textinput("enable") : $("#usu_csenha").textinput("disable");	
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#usu_modulos").textinput("enable") : $("#usu_modulos").textinput("disable"); 
						f_ElementsProperties("#usu_ativo"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
						
						f_storageready("sql_statments") == "Incluir" ? $("#usu_nome").focus() : $("#usu_senha").focus(); 					
						$("select").selectmenu("refresh");
					
						$("#crud_usuarios #menuopcoes ul").on( "click", "li", function() 
							{			
								if($(this).attr('id') == "Voltar") 
									{
										if( f_storageready("json_crudusu").indexOf("null") >= 0  )
											{  
												$(this).find("a").attr("href", "menu_principal.php");
												$("#crud_usuarios #menuopcoes ul").hasClass('ui-listview') ? $("#crud_usuarios #menuopcoes ul").listview('refresh') : $("#crud_usuarios #menuopcoes ul ").trigger('create');	
												return true;						
											}	
									}
								if($(this).attr('id') == "Gravar") 
									{
										$.mobile.loading("show");
										if($("#usu_senha").val() != $("#usu_csenha").val() ) 
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Verificar senha digitada, pois devem coincidir..." );
												f_storagewrite("page_redirect" , "crud_usuarios.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										if(ElementsValidation([ {"Id" : "usu_nome", "Caracteres" : "1"},{"Id" : "usu_senha", "Caracteres" : "1"}, {"Id" : "usu_modulos", "Caracteres" : "1"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_usuarios.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										if(f_storageready("sql_statments") == "Incluir") 
											{
												if(f_jsonarrayfind( JSON.parse(f_storageready("json_crudusu")), "usu_nome" , $("#usu_nome").val() ) >= 0 ) 
													{
														f_storagewrite("erro_mensagem" , "Transação não concluída, Nome : " + $("#usu_nome").val() + " já cadastrado... " );
														f_storagewrite("page_redirect" , "crud_usuarios.php" );
														$ .mobile.changePage ("error.php");
														return true;
													}
											}	
										
										var wsql = f_storageready("sql_statments") == "Incluir" ? $("#usu_nome").val() + "®" + 
																								  $("#usu_senha").val() + "®" +
																								  $("#usu_modulos").val() + "®" +
																								  $("#usu_ativo").val() + "©" : "" ; 
																	
										wsql = f_storageready("sql_statments") == "Alterar" ? $("#usu_senha").val() + "®" +
																							  $("#usu_modulos").val() + "®" + 
																							  $("#usu_ativo").val() + "®" +  
																							  $("#usu_nome").val() + "©" : wsql;
									
										wsql = f_storageready("sql_statments") == "Excluir" ? $("#usu_nome").val() + "®" +
																							  JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] + "©" : wsql;
										
										var waction = f_storageready("sql_statments") == "Incluir" ? "f_insert" : f_storageready("sql_statments") == "Alterar" ? "f_update" : "f_delete";
										
										$.getJSON('http://www.sisleq.com.br/maitre/admin/usu_processa.php?callback=?', {action: waction, banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql}, function(data)
											{
												$ .mobile.changePage ("usuarios.php");
											});
									}
							});
							
						$('#usu_codigo, #usu_senha, #usu_csenha, #usu_modulos, #usu_ativo').keypress(function(e)
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