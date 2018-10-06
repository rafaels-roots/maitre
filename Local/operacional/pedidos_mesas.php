<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
       
        <div data-role="page" id="pedidos_mesas">  
           
            <div data-role="header" >
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Pedidos</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content" >
				
				<div id="mes_null">&lt;Vazio&gt;</div>
				
				<ul data-role="listview" data-icon="false" id="list_PedidosMesas">
					
				</ul>
				
            </div>
			 
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			<script>
				
				
				$("#pedidos_mesas").on("pageshow", function()
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
					
						$("#pedidos_mesas #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#pedidos_mesas #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						//var json_mes =  "";
						$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql: "pedidos_mesas", par1 : JSON.parse(f_storageready("json_par"))[0]["par_garconexclusivo"], par2: ElementsAspa(JSON.parse( f_storageready("json_usu") )[0]["usu_nome"]) }, function(data)
							{
								//json_mes = data;
								if(JSON.stringify(data).indexOf("sql_record_null") >= 0 )
									{  
										$("#mes_null").css("display", "block");
									}
								else 
									{
										//$("#pedidos_mesas #list_PedidosMesas li").remove();
										f_listviewinsert( data , "mes_codigo", "mes_descricao", "#pedidos_mesas #list_PedidosMesas", "#" );
									}	
							});	
						
						$("#pedidos_mesas #list_PedidosMesas").on( "click", "li", function() 
							{
								f_storagewrite("mes_codigo",  $(this).children("a").attr("id"));
								f_storagewrite("mes_descricao", $(this).children("a").text());
											
								$("#list_PedidosMesas li a ").removeClass("ui-btn-active");
								$(this).find("a").addClass("ui-btn-active");
								$.mobile.loading("show");										
							
								$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', { action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql: "mesa_aberta_fechada", par1 : ElementsAspa($(this).children("a").attr("id")) }, function(data)
									{ 
										var wmes_aberta = "";
										if( JSON.stringify(data).indexOf("sql_record_null") >= 0 )
											{  
												wmes_aberta = "Fechada";
											}
										else 
											{
												if(data[0]["usu_nome"] != JSON.parse( f_storageready("json_usu") )[0]["usu_nome"]) 
													{
														f_storagewrite("erro_mensagem" , "Mesa pertence a outro garçon..." );
														f_storagewrite("page_redirect" , "menu_principal.php" );
														$ .mobile.changePage ("error.php");
														return true;	
													}
												wmes_aberta = "Aberta";
											}									
										f_storagewrite("wmes_aberta" , wmes_aberta);
										$ .mobile.changePage ("pedidos_itens.php");
									});
		
							});
						
					});
			</script>
			
			
			
        </div>
    </body>
</html>