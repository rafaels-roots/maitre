<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
     
        <div data-role="page" id="menu_impressoras">  
            
            <div data-role="header">
			<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                	
				<h1>Impressoras</h1>
				<div data-role="navbar">
					<ul>
						<li><a href="#" data-icon="home" data-theme="b" id="emp" charset="ISO-8859-1">...</a></li>
						<li><a href="#" data-icon="user" data-theme="b" id="usu" charset="ISO-8859-1">...</a></li> 
					</ul>
				</div>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content">
			
				<ul data-role="listview" data-icon="false" id="list_MenuImpressoras">
					
				</ul>
				
            </div>
            
			
			<div data-role="popup" data-history="false" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Voltar" data-icon="back"> <a href="#"> Voltar </a> </li>
				</ul>
			</div>
			
			<p id="json_dados" charset="ISO-8859-1"></p>
			<p id="json_impressora" charset="ISO-8859-1"></p>
			<p id="rel" charset="ISO-8859-1"></p>
			<p id="fil" charset="ISO-8859-1">0</p>
			<p id="interaction_menu_impressoras" charset="ISO-8859-1">SN</p>
			<p id="par" charset="ISO-8859-1"></p>
		
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
        
			<script>
				
				
				$("#menu_impressoras").on("pageshow", function() 
					{ 
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
							
						$("#menu_impressoras #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#menu_impressoras #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_imp = {action: "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql: "imp"  };
						$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', json_imp, function(data)
							{
								json_imp = data;
								f_listviewinsert(json_imp , "imp_codigo", "imp_local", "#list_MenuImpressoras", "#"); 
						
								$("#rel").text( f_storageready("rel") );
								$("#json_dados").text( f_storageready("json_dados") );
								$("#fil").text( f_storageready("fil") );
								
								$("#par").text( f_storageready("json_par") );
	
							});
						
						
						$("#list_MenuImpressoras").on("click", "li",  function()
							{
								$("#list_MenuImpressoras li a ").removeClass("ui-btn-active");
								$(this).find("a").addClass("ui-btn-active");
								
								$("#json_impressora").text(  "[" + JSON.stringify( json_imp[$(this).index()] ) + "]" );
								$("#interaction_menu_impressoras").text(f_storageready("interaction"));
							});
						
						
						$("#menu_impressoras #menuopcoes").on("click" , "#Voltar" , function()
							{								
								window.history.back();
							});
					});
			</script>
		
		
		</div>
		
		
		
    </body>
</html>
