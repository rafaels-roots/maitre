<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		  
    </head>
    <body>
     
        <div data-role="page" id="relatorio_visualizar">  
           
            <div data-role="header">
                <a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Visualizar</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
				<ul data-role="listview" id="list_Title" >
					<li  data-role="list-divider" id="title">
				
						Vendas
						<span class='ui-li-count' id='totalGeral'></span>
					</li>
				</ul>
            </div> 
            
            <div data-role="main" class="ui-content">
				
				<ul data-role="listview" data-icon="false" id="list_Visualizar">
					<li class="linhatodos">
						<a href="#" style='font-size:.92em;background-color:#dddddd;border-color:#dddddd'>
							 								
						</a>
					</li>	
				</ul>
					

            </div>
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Imprimir" data-icon="action"> <a href="menu_impressoras.php"> Imprimir </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_relatorio.php"> Voltar </a> </li>
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
			
				$("#relatorio_visualizar").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						
						$("#relatorio_visualizar #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#relatorio_visualizar  #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						
						$("#list_Visualizar .linhatodos a").html( f_storageready("rel_visualizarparametros") );
					
						var json_rel_opcoes = JSON.parse(f_storageready("json_dados"));
						var list_Visualizar = "";
						for(var i = 0, len = json_rel_opcoes.length; i < len; i++) 
							{						
								list_Visualizar += "<li>" +
														"<a href='#' style='padding-top:0;padding-bottom:0;'>" +
															"<h2>"+json_rel_opcoes[i]["mov_datahorapagamentovenda"]+"</h2>" +
															"<p style='font-size:.95em;'> ( "+json_rel_opcoes[i]["Qtde"]+" ) "+json_rel_opcoes[i]["descricao"]+ "</p>" +
															"<span class='ui-li-count' style='font-size:.88em;font-weight:700;'>" +  json_rel_opcoes[i]["mov_total"] + "</span>" +
														"</a>" +
													"</li>";
								
							}
						$("#list_Visualizar").append(list_Visualizar);	
						soma("#list_Visualizar li:not(.linhatodos)", "#relatorio_visualizar #totalGeral");
						
						$("#list_Visualizar, #list_Title").listview('refresh'); 
					});
			</script>
			
        </div>
		
</body>
</html>