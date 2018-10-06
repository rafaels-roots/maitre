<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
       
        <div data-role="page" id="despachar_mesas">  
           
            <div data-role="header" >
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Despachar</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content" >
				
				<div id="mes_null">&lt;Vazio&gt;</div>
				
				<ul data-role="listview" data-icon="false" id="list_DespacharMesas">
					
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
				
				
				$("#despachar_mesas").on("pageshow", function()
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
					
						$("#despachar_mesas #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#despachar_mesas #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
				
						$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action : "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], sql : "despachar_mesas", par1 : JSON.parse(f_storageready("json_par"))[0]["par_horarioverao"] , par2: JSON.parse(f_storageready("json_par"))[0]["par_garconexclusivo"], par3 : JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] }, function(data) 
							{
								if( data.length == 0 )
									{  
										$("#mes_null").css("display", "block");
									}
								else 
									{
										var list_DespacharMesas = "";
										for(var i = 0, len = data.length;  i < len; i++) 
											{						
												var display = data[i]["mov_produzido"] == "S" ? "inline" : "none";
												list_DespacharMesas += "<li>" +
																			"<a href='despachar_itens.php' style='padding-top:0;padding-bottom:0;'>" +
																				"<h2 id='"+data[i]["mes_codigo"]+"'>"+data[i]["mes_descricao"]+"</h2>" +
																				"<p style='font-size:.95em;'>Tempo : "+data[i]["mov_tempo"]+" Minutos </p>" +
																				"<span class='ui-li-count' style='display:"+display+"'>Ok....</span>" +	
																			"</a>" +
																		"</li>";
											}
										$("#list_DespacharMesas").append(list_DespacharMesas).listview("refresh");

									}
							});
							
						$("#despachar_mesas #list_DespacharMesas").on( "click", "li", function() 
							{
								f_storagewrite("mes_codigo", $(this).find("h2").attr("id"));
								f_storagewrite("mes_descricao", $(this).find("h2").text());
														
							});
						
					});
			</script>
			
			
			
        </div>
    </body>
</html>