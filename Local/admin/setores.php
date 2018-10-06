<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>

        <div data-role="page" id="setores">  
           
            <div data-role="header">
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Setores</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content" >
			
				<ul data-role="listview" data-icon="false" id="list_Setores">
					
				</ul>
				
            </div>
			 
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
			<div data-role="popup" id="setoropcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Incluir" data-icon="plus"> <a href="crud_setores.php"> Incluir </a> </li>
					<li id="Alterar" data-icon="recycle"> <a href="crud_setores.php"> Alterar </a> </li>
					<li id="Excluir" data-icon="minus"> <a href="crud_setores.php"> Excluir </a> </li>
					<li id="Voltar" data-icon="back"> <a href="#"> Voltar </a> </li>
					
				</ul>
			</div>
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			
			<script>
				$("#setores").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						
						$("#setores #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#setores #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_sto = {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "sto"  };
						
						$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', json_sto, function(data)
							{ 
								json_sto = data;
								f_storagewrite("json_sto", JSON.stringify(data) );
								if( JSON.stringify(data).indexOf("sql_record_null") >= 0  )
									{  
										f_storagewrite("sql_statments" ,  "Incluir" );
										$ .mobile.changePage ("crud_setores.php");
										return true;						
									}
								else
									{
										$("#list_Setores li").remove();
										var list_Setores = "";
										for(var i = 0, len = data.length; i < len; i++) 
											{	
												list_Setores += "<li>" +
																	"<a href='#setoropcoes' data-rel='popup' data-position-to='window'>" +
																		"<span id='set_DesValue'>"+data[i]["sto_descricao"]+"</span>"  +
																	"</a>" +
																"</li>"; 
											}
										$("#list_Setores").append(list_Setores).listview('refresh');
	
									}
							});

						
						
					
									
						
						$("#list_Setores").on( "click", "li", function() 
							{
								var json_stoclick = "[" + JSON.stringify( json_sto[$(this).index()] ) + "]" ;
								f_storagewrite("json_stoclick", json_stoclick);
							});
							
							
						$("#setoropcoes ul").on( "click", "li", function() 
							{
								if($(this).attr('id') == "Voltar") 
									{
										$("#setoropcoes").popup("close");
										return false;							
									}
								
										
								f_storagewrite("sql_statments" , $(this).attr('id') == "Incluir" ? "Incluir" : $(this).attr('id') == "Alterar" ? "Alterar" : "Excluir"  );
							});
					
							
					});
			</script>
			
			
        </div>
    </body>
</html>