<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>

        <div data-role="page" id="ocupacao">  
           
            <div data-role="header">
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Ocupação</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
				
				<ul data-role="listview" id="list_Title" >
					<li  data-role="list-divider" id="title"> 
						
					</li>
				</ul>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content" >
			
				<div id="mes_null">&lt;Vazio&gt;</div>
				
				<ul data-role="listview" data-icon="false" id="list_Ocupacao">
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
				$("#ocupacao").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						$("#ocupacao #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#ocupacao #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						$.getJSON('http://www.sisleq.com.br/maitre/admin/ocu_processa.php?callback=?', {action: "f_select",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] }, function(data)
							{
								if( data.length == 0)
									{  
										$("#list_Title").css("display" , "none");
										$("#mes_null").css("display", "block");
										return false;
									}
								else 
									{
										$("#ocupacao #title ").html( "Mesas" + "<span class='ui-li-count' id='totalGeral'></span>");	
										$("#list_Ocupacao li").remove();
										var list_Ocupacao = "";
										var totalGeral = 0;
										for(var i = 0, len = data.length; i < len; i++) 
											{	
												list_Ocupacao += "<li>" +
																	"<a href='#'>" +
																		"<span id='mes_DesValue'>"+data[i]["mes_descricao"]+"</span>"  +
																		"<span class='ui-li-count' style='font-size:.88em;font-weight:700;'>" +
																			"<span class='valor_Total'>" + data[i]["mov_valortotal"].replace(".","").replace(",",".") + "</span>" +
																		"</span>" +
																	"</a>" +
																"</li>";
												totalGeral += parseFloat(data[i]["mov_valortotal"].replace(".","").replace(",",".")); 				
											}
										$("#ocupacao #totalGeral").text(" R$ " + totalGeral.toFixed(2));	
										$("#list_Ocupacao").append(list_Ocupacao).listview("refresh");
										$("#list_Title").listview('refresh');	
									}		
							});	
			
					});	
			</script>
			
			
        </div>
    </body>
</html>