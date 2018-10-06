<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="crud_parametros">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Parâmetros</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
            <div data-role="main" class="ui-content">
				
				<div class="ui-corner-all custom-corners">
					<div class="ui-bar ui-bar-a" style="background-color: rgba(0, 89, 187, 0.71);color:#ffffff;text-shadow:none;">
						<h3 id="title_Formulario">...</h3>
					</div>
					<div class="ui-body ui-body-a">
						
						<form method="post" >
							
							<label for="par_garconexclusivo">Garçon Exclusivo : </label>
							<select id="par_garconexclusivo" name="par_garconexclusivo">
								<option selected value="S">Sim</option>
								<option value="N">Não</option>
							</select>
							
							<label for="par_observacaopedido">Observação no Pedido :  </label>
							<select id="par_observacaopedido" name="par_observacaopedido">
								<option selected value="S">Sim</option>
								<option value="N">Não</option>
							</select>
							
							<label for="par_ordemproduto">Ordem de Produto :</label>
							<select id="par_ordemproduto" name="par_ordemproduto">
								<option selected value="D">Descrição</option>
								<option value="P">Pessoal</option>
							</select>
							
							<label for="par_mensagemrecibo1">Mensagem Recibo : [1] </label>
							<input type="text" name="par_mensagemrecibo1" id="par_mensagemrecibo1" />
							
							<label for="par_mensagemrecibo2">Mensagem Recibo : [2] </label>
							<input type="text" name="par_mensagemrecibo2" id="par_mensagemrecibo2" />
							
							<label for="par_garconcomissao">Comissão do Garçon/100 : [%] </label>
							<input type="number" name="par_garconcomissao" id="par_garconcomissao" />
							
							<label for="par_horarioverao">Horário de Verão : </label>
							<select id="par_horarioverao" name="par_horarioverao">
								<option value="1">Sim</option>
								<option selected value="0">Não</option>
							</select>
							
							
						</form>
						
					</div>
				</div>	

            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			
			<script>
				$("#crud_parametros").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#crud_parametros #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#crud_parametros #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_par = {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "par"  } ;
						$.getJSON('http://localhost/maitre/processa_admin.php?callback=?', json_par, function(data)
							{
								json_par = data;
								if( JSON.stringify(data).indexOf("sql_record_null") >= 0  )
									{  
										$("#title_Formulario").text("Incluir");
										f_storagewrite("sql_statments" , "Incluir");	
									}
								else
									{
										$("#title_Formulario").text("Alterar");
										f_storagewrite("sql_statments" , "Alterar");
										$("#par_garconexclusivo").val(data[0]["par_garconexclusivo"]);
										$("#par_observacaopedido").val(data[0]["par_observacaopedido"]);
										$("#par_ordemproduto").val(data[0]["par_ordemproduto"]);
										$("#par_mensagemrecibo1").val(data[0]["par_mensagemrecibo1"] == null ? "" : data[0]["par_mensagemrecibo1"]);
										$("#par_mensagemrecibo2").val(data[0]["par_mensagemrecibo2"] == null ? "" : data[0]["par_mensagemrecibo2"]);
										$("#par_garconcomissao").val(data[0]["par_garconcomissao"] == null ? "" : data[0]["par_garconcomissao"]);
										$("#par_horarioverao").val(data[0]["par_horarioverao"]);
										$("select").selectmenu("refresh");
								
									}
								ElementsValidation( [  {"Id" : "par_mensagemrecibo1", "Caracteres" : "20"}, {"Id" : "par_mensagemrecibo2", "Caracteres" : "20"}, {"Id" : "par_garconcomissao", "Caracteres" : "5"}    ], "maxlength" );
							
							});
						
						$("#crud_parametros #menuopcoes ul").on( "click", "li", function() 
							{		
								if($(this).attr('id') == "Gravar") 
									{	
										$.mobile.loading("show");
										if(ElementsValidation([{"Id" : "par_mensagemrecibo1", "Caracteres" : "1"}, {"Id" : "par_mensagemrecibo2", "Caracteres" : "1"}, {"Id" : "par_garconcomissao", "Caracteres" : "1"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Transação não concluída, Campo Inválido..." );
												f_storagewrite("page_redirect" , "crud_parametros.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										
										var wsql = f_storageready("sql_statments") == "Incluir" ? "insert into par ( par_garconexclusivo, par_observacaopedido, par_ordemproduto, par_mensagemrecibo1, par_mensagemrecibo2, par_garconcomissao, par_horarioverao) values ("+ 
																								 ElementsAspa($("#par_garconexclusivo").val()) + "," + 
																								 ElementsAspa($("#par_observacaopedido").val()) + "," +
																								 ElementsAspa($("#par_ordemproduto").val()) + "," + 
																								 ElementsAspa($("#par_mensagemrecibo1").val()) + "," + 
																								 ElementsAspa($("#par_mensagemrecibo2").val()) + "," +
																								 ElementsAspa($("#par_garconcomissao").val()) + "," +
																								 ElementsAspa($("#par_horarioverao").val()) + ")" : ""; 
																	
										wsql = f_storageready("sql_statments") == "Alterar" ? "update par set par_garconexclusivo = " + ElementsAspa($("#par_garconexclusivo").val()) + "," +
																								"par_observacaopedido = " + ElementsAspa($("#par_observacaopedido").val()) + "," + 
																								"par_ordemproduto = " + ElementsAspa($("#par_ordemproduto").val()) + "," + 
																								"par_mensagemrecibo1 = " + ElementsAspa($("#par_mensagemrecibo1").val()) + "," + 
																								"par_mensagemrecibo2 = " + ElementsAspa($("#par_mensagemrecibo2").val()) + "," + 
																								"par_garconcomissao = " + ElementsAspa($("#par_garconcomissao").val()) + "," +
																								"par_horarioverao = " + ElementsAspa($("#par_horarioverao").val())	: wsql;
										
										var php_wret = {action: "f_multquery", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: wsql};										
										$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', php_wret, function(data)
											{
												$ .mobile.changePage ("menu_principal.php");
											});	
									}
							});
							
						$("#par_garconcomissao").keypress(function(e)
							{
								if( /[0123456789.]/.test(String.fromCharCode(e.keyCode)) ) 
									{ 	
										if($(this).val().length > 4) 
											{
												return false;	
											}
										return true;	
									}
								return false;
							});	
					
					
						$('#par_garconexclusivo, #par_observacaopedido, #par_ordemproduto, #par_mensagemrecibo1, #par_mensagemrecibo2, #par_garconcomissao').keypress(function(e)
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