<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Garçon</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
	</head>
    <body>
     
        <div data-role="page" id="menu_impressoras">  
            
            <div data-role="header" data-position="fixed">
			<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                	
				<h1>Impressoras</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp" charset="ISO-8859-1">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu" charset="ISO-8859-1">...</button></li> 
					</ul>
				</div>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content">
			
				<ul data-role="listview" data-icon="false" id="list_MenuImpressoras">	
				</ul>
				
            </div>
            
			
			
			
			<div data-role="popup" id="obs_impressao" style="min-width:250px;" >
				<div class="custom-corners ui-corner-all">
					<div class="ui-bar ui-bar-a" style="text-align:center;background-color:#4887cc;color:#ffffff;text-shadow:none;">
						<h3>Observação</h3>
					</div>
					<div class="ui-body ui-body-a">
						<form>
							<label for="cta_observacao" ></label>
							<textarea id="cta_observacao" name="cta_observacao" placeholder="Observação..."  ></textarea>
							<input type="button" id="ok_Observacao" value="Ok" style="text-shadow:none;">	  
						</form>
					</div>
				</div>
			</div>
			
			
			<div data-role="popup" data-history="false" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			<p id="json_dados" charset="ISO-8859-1"></p>
			<p id="json_impressora" charset="ISO-8859-1"></p>
			<p id="rel" charset="ISO-8859-1"></p>
			<p id="fil" charset="ISO-8859-1">0</p>
			<p id="interaction_menu_impressoras" charset="ISO-8859-1">SN</p>
			<p id="par" charset="ISO-8859-1"></p>
			<p id="set" charset="ISO-8859-1">*</p>
			<p id="b4a" charset="ISO-8859-1"></p>
			
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Garçon</h1>
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
						
						if(f_storagecheck("json_imp") == true) 
							{
								f_select_imp( JSON.parse( f_storageready("json_imp") ));	
							}
						else 
							{
								$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],	sql: "imp"}, function(data) 
									{
										if( data.length == 0 )
											{  
												f_storagewrite("erro_mensagem" , "Impressoras não cadastrada(s)..." );
												f_storagewrite("page_redirect" , "menu_principal.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										else 
											{
												f_storagewrite("json_imp", JSON.stringify(data));
												f_select_imp(data);	
											}	
									
									});
	
							}	
						$("#rel").text( f_storageready("rel") );
						$("#json_dados").text( f_storageready("json_dados") );
						$("#fil").text( f_storageready("fil") );
						$("#par").text( f_storageready("json_par") ); 
						
					
						
						$("#list_MenuImpressoras").on("click", "li",  function()
							{
								$("#list_MenuImpressoras li a ").removeClass("ui-btn-active");
								$(this).find("a").addClass("ui-btn-active");
								
								var json_imp = JSON.parse( f_storageready("json_imp") );
								$("#json_impressora").text(  "[" + JSON.stringify( json_imp[$(this).index()] ) + "]" );
								f_storageready("rel") ==  "cta" ? $("#obs_impressao").popup("open") : $("#interaction_menu_impressoras").text(f_storageready("interaction"));
							});
						
						$("#ok_Observacao").on("click", function()
							{
								 var par = JSON.parse(f_storageready("json_par"));
								 par[0]["par_ctaobservacao"] = $("#cta_observacao").val();
								 $("#par").text( JSON.stringify(par) ); 
						
								
								$("#interaction_menu_impressoras").text(f_storageready("interaction"));
								$("#obs_impressao").popup("close");
							});
						
						
						
						
					});
			</script>
		
		
		</div>
		
		
		
    </body>
</html>
