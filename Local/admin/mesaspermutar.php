<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
       
        <div data-role="page" id="mesaspermutar">  
           
            <div data-role="header" >
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Mesas</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div> 
            </div> 
            
			 
			
            <div data-role="main" class="ui-content" >
				
				<div id="mes_null">&lt;Vazio&gt;</div>
				
				<ul data-role="listview" data-icon="false" id="list_MesasPermutar">
					
				</ul>
				
            </div>
			 
			
			<div data-role="popup" id="permutarDestino" style="min-width:250px;" >
				<div class="custom-corners ui-corner-all">
					<div class="ui-bar ui-bar-a" style="text-align:center;background-color:#4887cc;color:#ffffff;text-shadow:none;">
						<h3>Destino</h3>
					</div>
					<div class="ui-body ui-body-a">
						<form>
							<label for="gar_mesa" ></label>
							<select name="gar_mesa" id="gar_mesa"></select>
							
							
							<input type="button" id="ok_Permutar" value="Ok" style="text-shadow:none;">	  
						</form>
					</div>
				</div>
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
				$("#mesaspermutar").on("pageshow", function()
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
					
						$("#mesaspermutar #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#mesaspermutar #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						var json_mes = {action: "f_select",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,	sql : "permutar_mesas" };
						$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', json_mes, function(data)
							{
								json_mes = data;
								if( JSON.stringify(data).indexOf("sql_record_null") >= 0 )
									{  
										$("#mes_null").css("display", "block");
										return false;
									}
								else 
									{
										$("#mesaspermutar #list_MesasPermutar li").remove();
										var list_MesasPermutar = "";	
										for(var i = 0, len = data.length; i < len; i++) 
											{						
												list_MesasPermutar += "<li><a href='#permutarDestino' data-rel='popup' style='padding-top:0;padding-bottom:0;'>" +
																			"<h2 id='mes_DesValue'>"+data[i]["mes_descricao"]+"</h2>" +
																			"<p style='font-size:.95em;'>Garçon : "+data[i]["usu_nome"]+"</p>" +
																		"</a></li>";
											}
										$("#list_MesasPermutar").append(list_MesasPermutar).listview("refresh");
									}		
							});
							
						var mes_destino = "";
						$("#list_MesasPermutar").on("click" , "li" , function()
							{
								mes_destino = json_mes[$(this).index()]["mes_codigo"];
								var json_usu =  {action: "f_select",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql : "permutar_garcon",par1: json_mes[$(this).index()]["usu_nome"] };
								$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', json_usu, function(data)
									{
										f_select("#gar_mesa", data, "usu_nome", "usu_nome", data[0]["usu_nome"]);
										$("select").selectmenu("refresh");	
									});
							});
						
						
						$("#ok_Permutar").on( "click", function() 
							{
								$.mobile.loading("show");										
								var php_ret_mov = {action: "f_permutargarcon",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],	mes : ElementsAspa(mes_destino), gar : $("#gar_mesa").val(), sql : "."   };
								$.getJSON('http://192.168.0.7/maitre/processa_admin.php?callback=?', php_ret_mov, function(data)
									{
										$ .mobile.changePage ("menu_principal.php");	
									});
							});
						
					});
			</script>
			
			
			
        </div>
    </body>
</html>