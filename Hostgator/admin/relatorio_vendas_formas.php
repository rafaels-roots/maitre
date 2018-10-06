<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="relatorio_vendas_formas">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Vendas</h1>
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
						<h3 id="title_Formulario">Opções</h3>
					</div>
					<div class="ui-body ui-body-a">
						
						<form method="post">
							
							<label for="fpg_descricao">Formas de Pagamento : </label>
							<select name="fpg_descricao" id="fpg_descricao">
								<option value="Todas" selected>Todas</option>
							</select>
							
							 
							<label for="ordem_vendas"> Ordem : </label>
							<select name="ordem_vendas" id="ordem_vendas">
								<option value="Rec">Rec</option>
								<option value="Dia" selected >Dia</option>
								<option value="Mes">Mês</option>
								
							</select>
							
							<label for="rel_periodoinicial">Período Inicial : </label>
							<input type="text" name="rel_periodoinicial" id="rel_periodoinicial" placeholder="dd/mm/aaaa hh:mm" value="01/01/2017 00:00" maxlength="16" data-wrapper-class="ui-custom" />
							
							<label for="rel_periodofinal">Período Final : </label>
							<input type="text" name="rel_periodofinal" id="rel_periodofinal"  placeholder="dd/mm/aaaa hh:mm" value="31/12/2017 00:00" maxlength="16" data-wrapper-class="ui-custom"/>
							
						</form>
						
					</div>
				</div>	

            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Visualizar" data-icon="bullets"> <a href="#"> Visualizar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_relatorio.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
			
				$("#relatorio_vendas_formas").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						
						$("#relatorio_vendas_formas #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#relatorio_vendas_formas #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$.getJSON('http://www.sisleq.com.br/maitre/admin/fpg_processa.php?callback=?', {action: "f_select_rel_fpg",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"]}, function(data)
							{
								var select_formas = "";
								for(var i = 0, len = data.length; i < len; i++) 
									{
										select_formas += "<option value='"+data[i]["fpg_codigo"]+"'>"+data[i]["fpg_descricao"]+"</option>";
									}		
								$("#fpg_descricao").append(select_formas).selectmenu("refresh");
							});
					
						$("#relatorio_vendas_formas").on("focus","input", function()
							{
								$.mobile.silentScroll($(this).offset().top);	
							});
						
						$("#relatorio_vendas_formas #menuopcoes").on( "click", "li", function() 
							{	
								if( $(this).attr('id') == "Visualizar" )
									{	
										$.mobile.loading("show");	
								
										if(ElementsValidation([{"Id" : "rel_periodoinicial", "Caracteres" : "16"}, {"Id" : "rel_periodofinal", "Caracteres" : "16"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Campo Inválido..." );
												f_storagewrite("page_redirect" , "relatorio_vendas_formas.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										
										var periodo_inicial = $("#rel_periodoinicial").val();
										var periodo_final = $("#rel_periodofinal").val();						
										periodo_inicial = periodo_inicial.substring(6,10) + "-" + periodo_inicial.substring(3,5) + "-" + periodo_inicial.substring(0,2) +  " " + periodo_inicial.substring(11,16)  ;
										periodo_final = periodo_final.substring(6,10) + "-" + periodo_final.substring(3,5) + "-" + periodo_final.substring(0,2) + " " + periodo_final.substring(11,16) ;
	
										var wfield = $("#ordem_vendas").val() == "Rec" || $("#ordem_vendas").val() == "Dia" ? " SUBSTR(DATE_FORMAT(pgt.pgt_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 1,5) as mov_datahorapagamentovenda, " : " SUBSTR(DATE_FORMAT(pgt.pgt_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 4,7) as mov_datahorapagamentovenda, "; 
										var gropy = $("#ordem_vendas").val() == "Rec" ? " pgt.pgt_datahorapagamento " : $("#ordem_vendas").val() == "Dia" ? " SUBSTR(DATE_FORMAT(pgt.pgt_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 1,5) " : " SUBSTR(DATE_FORMAT(pgt.pgt_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 4,7) ";
												
										var wwhere = $("#fpg_descricao").val() == "Todas" ? "  " : " and pgt.fpg_codigo = " + ElementsAspa($("#fpg_descricao").val());
											wwhere = wwhere + " and pgt.pgt_datahorapagamento >= " + ElementsAspa(periodo_inicial + ":00");
											wwhere = wwhere + " and pgt.pgt_datahorapagamento <= " + ElementsAspa(periodo_final + ":00"); 
											
										$.getJSON('http://www.sisleq.com.br/maitre/admin/fpg_processa.php?callback=?', {action: "f_print_rel_fpg" , banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , par1: wfield, par2 : wwhere, par3: gropy }, function(data)
											{
												if( data.length == 0  ) 
													{												
														f_storagewrite("erro_mensagem" , "Não há dados para imprimir ou visualizar com estes parâmetros..." );
														f_storagewrite("page_redirect" , "relatorio_vendas_formas.php" );
														$ .mobile.changePage ("error.php");
														return true;
													}
												else 
													{
														f_storagewrite("json_dados", JSON.stringify(data) );
														$.getJSON('http://www.sisleq.com.br/maitre/admin/fpg_processa.php?callback=?', {action: "f_datahora"}, function(data)
															{
																f_storagewrite("rel", "ven" );
																f_storagewrite("interaction", "SS action108" );
																f_storagewrite("fil", "Formas de Pagamento : " + $("#fpg_descricao option:selected").text() + "\n" + 
																					  "Ordem : " + $("#ordem_vendas option:selected").text() + "\n" +
																					  "Periodo(s) : " + $("#rel_periodoinicial").val() + " - " + $("#rel_periodofinal").val() + "\n" + 
																					  "Impresso em : " + data.DataHora );												
																	
																f_storagewrite("rel_visualizarparametros" ,  "Formas de Pagamento : " + $("#fpg_descricao option:selected").text() + ", <br />" + 
																											 "Ordem : " + $("#ordem_vendas option:selected").text() + ", <br />" +
																											 "Período : " + $("#rel_periodoinicial").val().substring(0, 18) + " - <br />" + $("#rel_periodofinal").val().substring(0, 18) + ", <br />" +
																											 "Impresso em : " + data.DataHora );
																$ .mobile.changePage ("relatorio_visualizar.php");
															});	
													}		
											});
									}		
							});
					
							
						$("#rel_periodoinicial, #rel_periodofinal").keypress(function(e)
							{
								if( /[\/:0123456789 ]/.test(String.fromCharCode(e.keyCode)) ) 
									{ 	
										return true;	
									}
								return false;
							});		
					
						$('#rel_periodoinicial, #rel_periodofinal').keypress(function(e)
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