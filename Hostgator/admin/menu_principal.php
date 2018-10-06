<!DOCTYPE html>
<html> 
   <head>  
	   <title>Maitre | Admin</title>  
	   <meta charset="utf-8">	
	   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />	
	   <meta name="viewport" content="width=device-width, initial-scale=1">
	  
   </head>  
   <body>
   
	   <div data-role="page" id="menu_principal">  
		   <div data-role="header">
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
					<li  data-role="list-divider"> Cadastros </li>	
					   <li><a href="mesas.php">Mesas</a></li>			
					   <li><a href="setores.php">Setores</a></li> 
					   <li><a href="grupos.php">Grupos</a></li>
					   <li><a href="produtos.php">Produtos</a></li>	
					   <li><a href="impressoras.php">Impressoras</a></li>	
					   <li><a href="formas.php">Formas de Pagamento</a></li>
					
					   
					<li data-role="list-divider"> Visualizar </li>	
						<li><a href="ocupacao.php">Ocupação</a></li>			
						<li><a href="mesaspermutar.php">Permutar Garçon</a></li>	
						
					<li data-role="list-divider"> Relatórios </li>	
					   									
					   <li id="rel_pro">		
							<a href="menu_impressoras.php">Produtos</a>	
					   </li>								
					  			
					   <li>
							<a href="menu_relatorio.php">Vendas</a>
					   </li>	
					 
					<li data-role="list-divider"> Configurações </li>	
					   <li> 
							<a href="usuarios.php">Usuários</a> 
					   </li>						
					   <li> 
							<a href="crud_parametros.php">Parâmetros</a> 
					   </li>									
		   
				</ul>		
			</div>
			
			
		<div data-role="footer" data-position="fixed">  
			<h1>Maitre | Admin</h1>          
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
					
					$("#rel_pro").on("click", function()		
						{																			
							$.mobile.loading("show");										
							$.getJSON('http://www.sisleq.com.br/maitre/processa_admin.php?callback=?', {action: "f_select",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "print_pro" }, function(data)
								{
									if(data[0]["value"] == "sql_record_null") 
										{								
											f_storagewrite("erro_mensagem" , "Não há produtos cadastrados..." );
											f_storagewrite("page_redirect" , "menu_principal.php" );
											$ .mobile.changePage ("error.php");
											return true;
										}
									f_storagewrite("rel", "pro"); 						
									f_storagewrite("interaction" , "SS action104" );				
									
									f_storagewrite("json_dados", JSON.stringify(data));	
									
								});	
						});	

				});			
		</script>
		
	</div>	
	
</body>

</html>