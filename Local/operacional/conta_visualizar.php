<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
  
   
    <body>
       
        <div data-role="page" id="conta_visualizar">  
           
            <div data-role="header" data-position="fixed">
			
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
				<h1>Conta</h1>
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
            
			 
			
            <div data-role="main" class="ui-content">
			
				<ul data-role="listview" data-icon="false" id="list_ContaVisualizar"  >
					
				</ul>
				
            </div>
           
		   
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					
					<li id="Imprimir" data-icon="check"> <a href="#"> Imprimir </a> </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			<script>
				$("#conta_visualizar").on("pageshow", function()
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 

						$("#conta_visualizar #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#conta_visualizar #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						
						//$("#conta_visualizar #list_ContaVisualizar li:not(.linhatodos)").remove();
						$("#conta_visualizar #title ").append( f_storageready('mes_descricao') + "<span class='ui-li-count' id='totalGeral' style='display:inline;'></span>");
						
						var json_mov_visualizar = JSON.parse(f_storageready("json_dados"));
						var list_ContaVisualizar = "";
						for(var i = 0, len = json_mov_visualizar.length; i < len; i++) 
							{						
								list_ContaVisualizar += "<li><a href='#' class='ui-btn'>" +
															"<h1 style='display:inline-block;vertical-align:middle;width:240px;margin-left:5px;'>" + json_mov_visualizar[i]["mov_qtde"] + " - " + json_mov_visualizar[i]["pro_descricao"] +  "</h1>" +
															
															"<p style='float:right;margin-right:-4px;font-size:.90em;font-weight:700;text-align:center;padding-right:.48em;padding-left:.48em;background-color:white;color:black;border:1px solid #dddddd;display:inline-block;border-radius:3px;'>" +
																"<span class='valor_Digitado'>"+ json_mov_visualizar[i]["mov_qtde"] + " x </span>" +
																"<span class='valor_Unitario'>" + json_mov_visualizar[i]["mov_valorunitario"].replace(",",".") + " = </span>" +
																"<span class='valor_Total'>" +json_mov_visualizar[i]["mov_valortotal"].replace(".","").replace(",",".") + "</span>" + 
															"</p>" +
														"</a></li>";					
							}
						$("#list_ContaVisualizar").append(list_ContaVisualizar).listview('refresh');
					
						
						var total = "";
						var totalgeral = 0;
						$.each($("#list_ContaVisualizar li .valor_Total"),function(i, value)
							{
								totalgeral = parseFloat(totalgeral) + parseFloat($(this).text().replace(",","."));
								
							});
							
						$("#conta_visualizar #totalGeral").text(" R$ " + totalgeral.toFixed(2));	
						$("#list_Title").listview('refresh'); 	
						
						
						
						$("#conta_visualizar #menuopcoes").on("click", "li", function()	 
							{
								if($(this).attr('id') == "Imprimir")
									{
										$("#menuopcoes li a ").removeClass("ui-btn-active");
										$(this).find("a").addClass("ui-btn-active");
										$.mobile.loading("show");										
										$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action: "f_datahora"}, function(data) 
											{
												f_storagewrite("rel", "cta");
												f_storagewrite("interaction", "SS action110");
												f_storagewrite("fil", "* " + f_storageready("mes_descricao").toUpperCase() + " * " +data.DataHora+ " " );
												
												$ .mobile.changePage ("menu_impressoras.php");
												
											});									
									}	
																			
							});
	
						
					});
				
			</script>
			
			
			
        </div>
		
		
</body>
</html>