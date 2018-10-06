<!DOCTYPE html>
<html> 
   <head>  
	   <title>Maitre | Admin</title>  
	   <meta charset="utf-8">	
	   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />	
	   <meta name="viewport" content="width=device-width, initial-scale=1">
	   
   </head>  
   <body>
   
	   <div data-role="page" id="menu_relatorio">  
		   <div data-role="header">
			    <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
			   <h1>Relatórios</h1>
			   
			   <div data-role="navbar">	
				   <ul>
					   <li><button data-icon="home" data-theme="b" id="emp">...</button></li>	
					   <li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
				   </ul>	
			   </div>   
			</div>  

			
		   <div data-role="main" class="ui-content">	
				<ul data-role="listview" data-icon="false" id="list_MenuRelatorio">				
					   <li><a href="relatorio_vendas_mesas.php">Mesas</a></li>			
					   <li><a href="relatorio_vendas_usuarios.php">Garçons</a></li> 
					   <li><a href="relatorio_vendas_grupos.php">Grupos</a></li>	
					   <li><a href="relatorio_vendas_produtos.php">Produtos</a></li>	
					   <li><a href="relatorio_vendas_formas.php">Formas de Pagamento</a></li>
				</ul>		
			</div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			<div data-role="footer" data-position="fixed">  
				<h1>Maitre | Admin</h1>          
			</div>	
		
		<script>				
			$("#menu_relatorio").on("pageshow", function() 	
				{ 			
					if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
					
					$("#menu_relatorio #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );	
					$("#menu_relatorio #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
					
				});			
		</script>
		
	</div>	
	
</body>

</html>