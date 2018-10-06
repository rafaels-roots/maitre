<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_formas">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Formas</h1>
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
							
							<label for="fpg_codigo">Código : [0-9] </label>
							<input type="number" name="fpg_codigo" id="fpg_codigo" data-wrapper-class="ui-custom" disabled>
							
							<label for="fpg_descricao">Descrição: </label>
							<input type="text" id="fpg_descricao" name="fpg_descricao" data-wrapper-class="ui-custom" maxlength="30" />
						   
							<label for="fpg_ativa">Ativa : </label>
							<select id="fpg_ativa" name="fpg_ativa">
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
					<li id="Voltar" data-icon="back"> <a href="formas.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
				$("#crud_formas").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#crud_formas #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_formas #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$("#title_Formulario").text(f_storageready("sql_statments"));
						f_ElementsProperties("#fpg_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_fpgclick"))[0]["fpg_codigo"] );
						f_ElementsProperties("#fpg_descricao"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_fpgclick"))[0]["fpg_descricao"] );
						f_ElementsProperties("#fpg_ativa"	,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_fpgclick"))[0]["fpg_ativa"] );
						 
						f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#fpg_descricao").textinput("enable") : $("#fpg_descricao").textinput("disable"); 
						f_ElementsProperties("#fpg_ativa"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
						
						$("#fpg_descricao").focus(); 
						$("select").selectmenu("refresh");
						
						$("#crud_formas #menuopcoes ul").on( "click", "li", function() 
							{	
								if($(this).attr('id') == "Voltar") 
									{
										if( f_storageready("json_fpg").indexOf("null") >= 0  )
											{  
												$(this).find("a").attr("href", "menu_principal.php");
												$("#crud_formas #menuopcoes ul").hasClass('ui-listview') ? $("#crud_formas #menuopcoes ul").listview('refresh') : $("#crud_formas #menuopcoes ul ").trigger('create');	
												return true;						
											}	
									}

							
								if($(this).attr('id') == "Gravar") 
									{
										$.mobile.loading("show");
										if(ElementsValidation([ {"Id" : "fpg_descricao", "Caracteres" : "1"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_formas.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										var wsql = f_storageready("sql_statments") == "Incluir" ?  $("#fpg_descricao").val() + "®" +
																								   $("#fpg_ativa").val() + "©" : "" ; 
																		
										wsql = f_storageready("sql_statments") == "Alterar" ? $("#fpg_descricao").val() + "®" +
																							  $("#fpg_ativa").val() + "®" +
																							  $("#fpg_codigo").val() + "©" : wsql;
																					
										wsql = f_storageready("sql_statments") == "Excluir" ? $("#fpg_codigo").val() + "©" : wsql;
										
										var waction = f_storageready("sql_statments") == "Incluir" ? "f_insert" : f_storageready("sql_statments") == "Alterar" ? "f_update" : "f_delete";

										$.getJSON('http://www.sisleq.com.br/maitre/admin/fpg_processa.php?callback=?', {action: waction, banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql}, function(data)
											{ 
												if(f_storageready("sql_statments") == "Excluir")
													{
														if(data.rows <= 0) 
															{
																f_storagewrite("erro_mensagem" , "Transação não concluída, Forma de Pagamento com movimentação..." );
																f_storagewrite("page_redirect" , "formas.php" );
																$ .mobile.changePage ("error.php");
																return true;
															}
													}
												$ .mobile.changePage ("formas.php");
											});
										
									}
							});

						
						$('#fpg_descricao, #fpg_ativa').keypress(function(e)
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