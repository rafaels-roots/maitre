<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charsto="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_setores">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Setores</h1>
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
							
							<label for="sto_codigo">Código : [0-9] </label>
							<input type="number" name="sto_codigo" id="sto_codigo" disabled>
							
							<label for="sto_descricao">Descrição : </label>
							<input type="text" id="sto_descricao" name="sto_descricao" />
						
							<label for="sto_ativo">Ativo :  </label>
							<select id="sto_ativo" name="sto_ativo">
								<option value="S">Sim</option>
								<option value="N">Não</option>
							</select>
							
							<label for="imp_codigo">Ativo :  </label>
							<select id="imp_codigo" name="imp_codigo">
								
							</select>
							    
						</form>
					</div>
				</div>
				
            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-insto="true"  >
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="setores.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			
			<script>
				$("#crud_setores").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#crud_setores #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_setores #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						
						var select_impsto = {action: "f_select",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "select_impsto" };
						$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', select_impsto, function(data)
							{
								f_select("#imp_codigo", data, "imp_codigo", "imp_local", data[0]["imp_codigo"]);
						
								$("#title_Formulario").text(f_storageready("sql_statments"));
								f_ElementsProperties("#sto_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_stoclick"))[0]["sto_codigo"] );
								f_ElementsProperties("#sto_descricao"	,"value", f_storageready("sql_statments") == "Incluir" ? "" : JSON.parse(f_storageready("json_stoclick"))[0]["sto_descricao"]  );
								f_ElementsProperties("#sto_ativo"	,"value", f_storageready("sql_statments") == "Incluir" ? "S" : JSON.parse(f_storageready("json_stoclick"))[0]["sto_ativo"]  );
								f_ElementsProperties("#imp_codigo"	,"value", f_storageready("sql_statments") == "Incluir" ?  data[0]["imp_codigo"] : JSON.parse(f_storageready("json_stoclick"))[0]["imp_codigo"]  );
								 
								f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? $("#sto_descricao").textinput("enable") : $("#sto_descricao").textinput("disable"); 
								f_ElementsProperties("#sto_ativo"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								f_ElementsProperties("#imp_codigo"	,"disabled", f_storageready("sql_statments") == "Incluir" || f_storageready("sql_statments") == "Alterar" ? false : true);
								
								$("#sto_descricao").focus(); 
								$("select").selectmenu("refresh");
						
								ElementsValidation( [ {"Id" : "sto_descricao", "Caracteres" : "30"}], "maxlength" );
							});
						$("#crud_setores #menuopcoes ul").on( "click", "li", function() 
							{			
								if($(this).attr('id') == "Voltar") 
									{
										if( f_storageready("json_sto").indexOf("null") >= 0  )
											{  
												$(this).find("a").attr("href", "menu_principal.php");
												$("#crud_setores #menuopcoes ul").hasClass('ui-listview') ? $("#crud_setores #menuopcoes ul").listview('refresh') : $("#crud_setores #menuopcoes ul ").trigger('create');	
												return true;						
											}	
									}
								if($(this).attr('id') == "Gravar") 
									{
										$.mobile.loading("show");
										if(ElementsValidation([ {"Id" : "sto_descricao", "Caracteres" : "1"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_setores.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										
										var wsql = f_storageready("sql_statments") == "Incluir" ? "insert into sto ( sto_descricao,  sto_ativo, imp_codigo) values ("+ 
																								 ElementsAspa($("#sto_descricao").val()) + "," +
																								 ElementsAspa($("#sto_ativo").val()) + "," +
																								 $("#imp_codigo").val() + ")" : "" ; 
																	
										wsql = f_storageready("sql_statments") == "Alterar" ? "update sto set sto_descricao = " + ElementsAspa($("#sto_descricao").val()) + "," +
																					"sto_ativo = " + ElementsAspa($("#sto_ativo").val()) + "," +
																					"imp_codigo = " + $("#imp_codigo").val() + 
																					" where sto_codigo =  " + ElementsAspa($("#sto_codigo").val()) : wsql;
																					
										wsql = f_storageready("sql_statments") == "Excluir" ? "delete from sto where sto_codigo = " + ElementsAspa($("#sto_codigo").val()) : wsql;
									
										
										var php_wret = {action: "f_multquery", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql};
										$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', php_wret, function(data)
											{
												$ .mobile.changePage ("setores.php");
											});
											
									}
							});
							
						$('#sto_descricao, #sto_ativo, #imp_codigo').keypress(function(e)
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