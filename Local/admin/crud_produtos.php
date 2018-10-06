<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_produtos">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Produtos</h1>
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
							
							<label for="pro_codigo">Código : [0-9] </label>
							<input type="number" name="pro_codigo" id="pro_codigo" disabled>
							
							<label for="pro_descricao">Descrição : </label>
							<input type="text" id="pro_descricao" name="pro_descricao" />
						
							<label for="pro_valor">Valor : </label>
							<input type="number" id="pro_valor" name="pro_valor" />
							
							<label for="pro_ordem">Ordem : [0-9] </label>
							<input type="number" name="pro_ordem" id="pro_ordem">
							
							<label for="pro_unidade">Unidade :  </label>
							<select id="pro_unidade" name="pro_unidade">
								<option value="u" selected>Unidade</option>
								<option value="k">Kilo</option>
								<option value="l">Litro</option>
							</select>
							
							<label for="pro_ativo">Ativo : </label>
							<select id="pro_ativo" name="pro_ativo">
								<option value="S" selected>Sim</option>
								<option value="N">Não</option>
							</select>
							
							<label for="sto_codigo">Setor :  </label>
							<select name="sto_codigo" id="sto_codigo"></select>
							
							<label for="gru_codigo">Grupo :  </label>
							<select name="gru_codigo" id="gru_codigo"></select>
							
							<label for="pro_visto">Visto : </label>
							<select name="pro_visto" id="pro_visto">
								<option value="S">Sim</option>
								<option value="N" selected>Não</option>
							</select>
							
							<label for="pro_produzido">Produzido : </label>
							<select name="pro_produzido" id="pro_produzido">
								<option value="S">Sim</option>
								<option value="N" selected>Não</option>
							</select>
						
							<label for="pro_comanda">Comanda : </label>
							<select name="pro_comanda" id="pro_comanda">
								<option value="S">Sim</option>
								<option value="N" selected>Não</option>
							</select>
						
							
							
						</form>
					</div>
				</div>
				
            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true">
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="produtos.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
				$("#crud_produtos").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#crud_produtos #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_produtos #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_select = {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "grusto" };
						$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', json_select, function(data)
							{
								var gru_default = 0;
								var sto_default = 0;
								var select_Grupos = "";
								var select_Setores = "";	
								for(var i = 0, len = data.length; i < len; i++) 
									{
										if(data[i]["tb"] == "gru") 
										{
											gru_default = gru_default >= i ? i : gru_default;
											select_Grupos += "<option value='"+ data[i]["codigo"] +"' >" + data[i]["descricao"] +"</option>";	
										}
										if(data[i]["tb"] == "sto") 
										{
											sto_default = sto_default <= 0 ? i : sto_default;
											select_Setores += "<option value='"+ data[i]["codigo"] +"' >" + data[i]["descricao"] +"</option>";	
										}
									}
								$("#gru_codigo").append(select_Grupos);
								$("#sto_codigo").append(select_Setores);
								
								
								$("#title_Formulario").text(f_storageready("sql_statments"));
								f_ElementsProperties("#pro_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_proclick"))[0]["pro_codigo"] );
								f_ElementsProperties("#pro_descricao"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_proclick"))[0]["pro_descricao"] );
								f_ElementsProperties("#pro_unidade"	,"value", f_storageready("sql_statments") == "Incluir" ? "u" : JSON.parse(f_storageready("json_proclick"))[0]["pro_unidade"] );
								f_ElementsProperties("#pro_valor"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_proclick"))[0]["pro_valor"] );
								f_ElementsProperties("#pro_ativo"	,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_proclick"))[0]["pro_ativo"] );
								f_ElementsProperties("#pro_ordem"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_proclick"))[0]["pro_ordem"] );
								f_ElementsProperties("#sto_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? data[sto_default]["codigo"] : JSON.parse(f_storageready("json_proclick"))[0]["sto_codigo"] );
								f_ElementsProperties("#gru_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? data[gru_default]["codigo"] : JSON.parse(f_storageready("json_proclick"))[0]["gru_codigo"] );
								f_ElementsProperties("#pro_visto"	,"value", f_storageready("sql_statments") == "Incluir" ? "N" : JSON.parse(f_storageready("json_proclick"))[0]["pro_visto"] );
								f_ElementsProperties("#pro_produzido" ,"value", f_storageready("sql_statments") == "Incluir" ? "N" : JSON.parse(f_storageready("json_proclick"))[0]["pro_produzido"] );
								f_ElementsProperties("#pro_comanda" ,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_proclick"))[0]["pro_comanda"] );
										
								f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#pro_descricao").textinput("enable") : $("#pro_descricao").textinput("disable"); 
								f_ElementsProperties("#pro_unidade"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#pro_valor").textinput("enable") : $("#pro_valor").textinput("disable"); 
								f_ElementsProperties("#pro_ativo"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#pro_ordem").textinput("enable") : $("#pro_ordem").textinput("disable"); 
								f_ElementsProperties("#sto_codigo"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_ElementsProperties("#gru_codigo"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_ElementsProperties("#pro_visto"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_ElementsProperties("#pro_produzido"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_ElementsProperties("#pro_comanda"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								
								$("#pro_descricao").focus();
								$("select").selectmenu("refresh");
								
								ElementsValidation( [ {"Id" : "pro_descricao", "Caracteres" : "60"}, {"Id" : "pro_valor", "Caracteres" : "6"}, {"Id" : "pro_ordem", "Caracteres" : "4"} ], "maxlength" );
					 
									
							});
					
						
						$("#crud_produtos #menuopcoes ul").on( "click", "li", function() 
							{			
								if($(this).attr('id') == "Voltar") 
									{
										if( f_storageready("json_pro").indexOf("null") >= 0  )
											{  
												$(this).find("a").attr("href", "menu_principal.php");
												$("#crud_produtos #menuopcoes ul").hasClass('ui-listview') ? $("#crud_produtos #menuopcoes ul").listview('refresh') : $("#crud_produtos #menuopcoes ul ").trigger('create');	
												return true;						
											}	
									}
			
								if($(this).attr('id') == "Gravar") 
									{
										$.mobile.loading("show");
										if(ElementsValidation([ {"Id" : "pro_descricao", "Caracteres" : "1"}, {"Id" : "pro_valor", "Caracteres" : "1"}, {"Id" : "pro_ativo", "Caracteres" : "1"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_produtos.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
																		
										var wsql = f_storageready("sql_statments") == "Incluir" ? "insert into pro (pro_descricao, pro_unidade, pro_valor, pro_ativo, pro_ordem, sto_codigo, gru_codigo , pro_visto, pro_produzido, pro_comanda) values ("+ 
																								 ElementsAspa($("#pro_descricao").val()) + "," +
																								 ElementsAspa($("#pro_unidade").val()) + "," +
																								 ElementsAspa($("#pro_valor").val()) + "," + 
																								 ElementsAspa($("#pro_ativo").val()) + "," +
																				 				 ElementsAspa($("#pro_ordem").val()) + "," +
																								 ElementsAspa($("#sto_codigo").val()) + "," +
																								 ElementsAspa($("#gru_codigo").val()) + "," + 
																								 ElementsAspa($("#pro_visto").val()) + "," +
																								 ElementsAspa($("#pro_produzido").val()) + "," +
																								 ElementsAspa($("#pro_comanda").val()) + ")" : "" ; 
						
										wsql = f_storageready("sql_statments") == "Alterar" ? "update pro set pro_descricao = " + ElementsAspa($("#pro_descricao").val()) + "," +
																								"pro_unidade = " + ElementsAspa($("#pro_unidade").val()) + "," +
																								"pro_valor = " + ElementsAspa($("#pro_valor").val()) + "," +
																								"pro_ativo = " + ElementsAspa($("#pro_ativo").val()) + "," +
																								"pro_ordem = " + ElementsAspa($("#pro_ordem").val()) + "," +
																								"sto_codigo = " + ElementsAspa($("#sto_codigo").val()) + "," +
																								"gru_codigo = " + ElementsAspa($("#gru_codigo").val()) + "," +
																								"pro_visto = " + ElementsAspa($("#pro_visto").val()) + "," +
																								"pro_produzido = " + ElementsAspa($("#pro_produzido").val()) + "," +
																								"pro_comanda = " + ElementsAspa($("#pro_comanda").val()) +	 
																								" where pro_codigo =  " + ElementsAspa($("#pro_codigo").val()) : wsql;
																					
										wsql = f_storageready("sql_statments") == "Excluir" ? "delete from pro where pro_codigo = " + ElementsAspa($("#pro_codigo").val()) +
																								" and not pro_codigo in (select mov.pro_codigo from mov mov) "	: wsql;
									
										
										var php_wret = {action: "f_multquery", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql};
										$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', php_wret, function(data)
											{
												if(f_storageready("sql_statments") == "Excluir")
													{
														if(data.rows <= 0) 
															{
																f_storagewrite("erro_mensagem" , "Transação não concluída, Produto com movimentação..." );
																f_storagewrite("page_redirect" , "produtos.php" );
																$ .mobile.changePage ("error.php");
																return true;
															}
													}
												$ .mobile.changePage ("produtos.php");
											});
										
											
									}
							});
						
						$("#pro_ordem").keypress(function(e)
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
						
						$("#pro_valor").keypress(function(e)
							{

								if( /[0123456789.]/.test(String.fromCharCode(e.keyCode)) ) 
									{ 
										
										return true;
									}
									return false;
							});	
						$('#pro_descricao, #pro_unidade, #pro_valor, #pro_ativo, #pro_ordem, #sto_codigo, #gru_codigo , #pro_visto, #pro_produzido').keypress(function(e)
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