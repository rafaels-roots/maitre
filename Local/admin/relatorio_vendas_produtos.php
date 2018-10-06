<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		     
    </head>
    <body>
     
        <div data-role="page" id="relatorio_vendas_produtos">  
           
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
							
							<label for="pro_descricao">Produtos : </label>
							<select name="pro_descricao" id="pro_descricao">
								<option value="Todos" selected>Todos</option>
							</select>
							
							 <label for="mov_status"> Vendas : </label>
							<select name="mov_status" id="mov_status">
								<option value="G" selected>Pagas</option>
								<option value="C">Canceladas</option>
							</select>
							
							<label for="ordem_vendas"> Ordem : </label>
							<select name="ordem_vendas" id="ordem_vendas">
								<option value="Dia" selected>Dia</option>
								<option value="Mes">Mês</option>
								
							</select>
							
							<label for="rel_periodoinicial">Período Inicial : </label>
							<input type="text" name="rel_periodoinicial" id="rel_periodoinicial" maxlength="16" placeholder="dd/mm/aaaa hh:mm" value="01/01/2017 00:00" />
							
							<label for="rel_periodofinal">Período Final : </label>
							<input type="text" name="rel_periodofinal" id="rel_periodofinal" maxlength="16"  placeholder="dd/mm/aaaa hh:mm" value="31/12/2017 00:00" />
							
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
			
				$("#relatorio_vendas_produtos").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						
						$("#relatorio_vendas_produtos #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#relatorio_vendas_produtos #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_select = {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "select_rel_pro"};
						$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', json_select, function(data)
							{
								var select_produtos = "";
								for(var i = 0, len = data.length; i < len; i++) 
									{
										select_produtos += "<option value='"+data[i]["pro_codigo"]+"'>"+data[i]["pro_descricao"]+"</option>";
									}
								$("#pro_descricao").append(select_produtos).selectmenu("refresh");
							});
						
						$("#relatorio_vendas_produtos").on("focus","input", function()
							{
								$.mobile.silentScroll($(this).offset().top);	
							});
							
						$("#relatorio_vendas_produtos #menuopcoes").on( "click", "li", function() 
							{		
								if( $(this).attr('id') == "Visualizar" )
									{	
										$.mobile.loading("show");
										if(ElementsValidation([{"Id" : "rel_periodoinicial", "Caracteres" : "16"}, {"Id" : "rel_periodofinal", "Caracteres" : "16"}], "minlength") == false)
											{
												f_storagewrite("erro_mensagem" , "Campo Inválido..." );
												f_storagewrite("page_redirect" , "relatorio_vendas_produtos.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										
										var periodo_inicial = $("#rel_periodoinicial").val();
										var periodo_final = $("#rel_periodofinal").val();						
										periodo_inicial = periodo_inicial.substring(6,10) + "-" + periodo_inicial.substring(3,5) + "-" + periodo_inicial.substring(0,2) +  " " + periodo_inicial.substring(11,16)  ;
										periodo_final = periodo_final.substring(6,10) + "-" + periodo_final.substring(3,5) + "-" + periodo_final.substring(0,2) + " " + periodo_final.substring(11,16) ;
										
										var wfield= "";
										var gropy = "";
										if($("#ordem_vendas").val() == "Dia") {
										   if($("#mov_status").val() == "G") {
											   wfield = " SUBSTR(DATE_FORMAT(mov.mov_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 1,5) as mov_datahorapagamentovenda, ";
											   gropy = " SUBSTR(DATE_FORMAT(mov.mov_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 1,5)";
										   }
											else {
												wfield = " SUBSTR(DATE_FORMAT(mov.mov_datahoravenda, '%d/%m/%Y %H:%i:%s'), 1,5) as mov_datahorapagamentovenda, ";
												gropy = " SUBSTR(DATE_FORMAT(mov.mov_datahoravenda, '%d/%m/%Y %H:%i:%s'), 1,5)";
											}
										}
										else{
											if($("#mov_status").val() == "G") {
												wfield = " SUBSTR(DATE_FORMAT(mov.mov_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 4,7) as mov_datahorapagamentovenda, ";
												gropy = " SUBSTR(DATE_FORMAT(mov.mov_datahorapagamento, '%d/%m/%Y %H:%i:%s'), 4,7) ";	
											}
											else{
												wfield = " SUBSTR(DATE_FORMAT(mov.mov_datahoravenda, '%d/%m/%Y %H:%i:%s'), 4,7) as mov_datahorapagamentovenda, ";
												gropy = " SUBSTR(DATE_FORMAT(mov.mov_datahoravenda, '%d/%m/%Y %H:%i:%s'), 4,7) ";
											}	
										}
										var wwhere =  $("#mov_status").val() == "G" ?  " mov.mov_status = 'G' " :  " mov.mov_status = 'C' ";
											wwhere = $("#pro_descricao").val() == "Todos" ? wwhere + "" : wwhere + " and mov.pro_codigo = " + ElementsAspa($("#pro_descricao").val());
											wwhere = $("#mov_status").val() == "G" ? wwhere + " and mov.mov_datahorapagamento >= " + ElementsAspa(periodo_inicial + ":00") : wwhere + " and mov.mov_datahoravenda >= " + ElementsAspa(periodo_inicial + ":00");
											wwhere = $("#mov_status").val() == "G" ? wwhere + " and mov.mov_datahorapagamento <= " + ElementsAspa(periodo_final + ":00") : wwhere + " and mov.mov_datahoravenda <= " + ElementsAspa(periodo_final + ":00");
										var orderby = $("#mov_status").val() == "G" ? " mov.mov_datahorapagamento " : " mov.mov_datahoravenda "; 	
			
										var rel_opcoes = {action: "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,	sql: "print_rel_pro"  , par1: wfield, par2:wwhere, par3:gropy, par4: orderby };
										$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', rel_opcoes, function(data)
											{
												if( JSON.stringify(data).indexOf("sql_record_null") >= 0  ) 
													{												
														f_storagewrite("erro_mensagem" , "Não há dados para imprimir ou visualizar com estes parâmetros..." );
														f_storagewrite("page_redirect" , "relatorio_vendas_produtos.php" );
														$ .mobile.changePage ("error.php");
														return true;
													}
												else
													{
														f_storagewrite("json_dados", JSON.stringify(data) );
														$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', {action:"f_datahora"}, function(data)
															{
																f_storagewrite("rel", "ven" );
																f_storagewrite("interaction", "SS action108" );
																f_storagewrite("fil", "Produtos(s) : " + $("#pro_descricao option:selected").text() + "\n" + 
																					  "Pgto(s) : " + $("#mov_status option:selected").text() + "\n" +   
																					  "Ordem : " + $("#ordem_vendas option:selected").text() + "\n" +
																					  "Periodo(s) : " + $("#rel_periodoinicial").val() + " - " + $("#rel_periodofinal").val() + "\n" + 
																					  "Impresso em : " + data.DataHora );												
													
																f_storagewrite("rel_visualizarparametros" ,"Produtos : " + $("#pro_descricao option:selected").text() + ", <br />" +
																										   "Pgto : " + $("#mov_status option:selected").text() + ", <br />" +
																										   "Ordem : " + $("#ordem_vendas option:selected").text() + ", <br />" +
																										   "Período : " + $("#rel_periodoinicial").val().substring(0, 18) + " - <br />" + $("#rel_periodofinal").val().substring(0, 18) + ", <br />" +
																										   "Impresso em : " + data.DataHora);
										
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