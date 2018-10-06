<!DOCTYPE html>
<html> 
   <head>  
	   <title>Maitre | Operacional</title>  
	   <meta charset="utf-8">	
	   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />	
	   <meta name="viewport" content="width=device-width, initial-scale=1">
	  
   </head>  
   <body>
   
	   <div data-role="page" id="menu_principal">  
		   <div data-role="header" data-position="fixed">
			   <h1>Menu</h1>
			   
			   <div data-role="navbar">	
				   <ul>
					   <li><button data-icon="home" data-theme="b" id="emp">...</button></li>	
					   <li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
				   </ul>	
			   </div>   
			</div>  

			
		   <div data-role="main" class="ui-content">	
				<ul data-role="listview" data-icon="false" id="list_MenuPrincipal">			
					<li data-role="list-divider" alt="Pedidos"> Pedidos </li>	
					   <li alt="Pedidos" id="Incluir"><a href="pedidos_mesas.php">Incluir</a></li>
					   <li alt="Pedidos" id="Comandar_Setores"><a href="comandar_mesas.php">Comandar</a></li>					   
					   <li alt="Pedidos" id="Despachar"><a href="despachar_mesas.php">Despachar</a></li> 
					   <li alt="Pedidos" id="Permutar"><a href="permutar_mesas.php">Permutar</a></li>	
					   <li alt="Pedidos" id="Cancelar"><a href="cancelar_pedidos_mesas.php">Cancelar</a></li>	
					   <li alt="Pedidos" id="Conta"><a href="conta_pedidos_mesas.php">Conta</a></li>
						
					<li data-role="list-divider" alt="Chef"> Chef </li>
					
						<li alt="Chef" id="Mesas_Geral"><a href="mesas_geral.php">Geral</a></li>
						<li alt="Chef" id="Mesas_Setor"><a href="setores.php">Setor</a></li>		
					
					<li data-role="list-divider" alt="Caixa"> Caixa </li>
						<li alt="Caixa" id="Conta"><a href="conta_caixa_mesas.php">Conta</a></li>		
						<li alt="Caixa" id="Pagamento_Itens"><a href="pagamento_mesas.php">Pagamento</a></li>	
						<li alt="Caixa" id="Cancelar"><a href="cancelar_caixa_mesas.php">Cancelar</a></li>

					
		   
				</ul>		
			</div>
			
			
		<div data-role="footer" data-position="fixed">  
			<h1>Maitre | Operacional</h1>          
		</div>	
		
		<script>				
			$("#menu_principal").on("pageshow", function() 	
				{ 			
					if(f_storagecheck("mob") == false) 
						{
							location.href="notfound.php";
							return true;	
						}		 
				
					$("#menu_principal #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );	
					$("#menu_principal #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
					
					$.each( $("#list_MenuPrincipal li"), function(i, v)
						{
							if( JSON.parse( f_storageready("json_usu") )[0]["usu_modulos"].lastIndexOf( $(this).attr("alt")  ) >= 0 )
								{  
									$(this).css("display", "list-item");
								}	
						});
						
						
						
						
					
				});			
		</script>
		
	</div>	
	
</body>

</html>