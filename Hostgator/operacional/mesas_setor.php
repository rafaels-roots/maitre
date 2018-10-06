<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
    </head>
    <body>
       
        <div data-role="page" id="mesas_setor">  
           
            <div data-role="header">
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
				
				<div data-role="collapsibleset" id="collapsible_MesasSetor"></div>		
				
            </div>
			 
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Atualizar" data-icon="refresh"> <a href="#"> Atualizar </a> </li>
					<li id="Voltar" data-icon="back"> <a href="setores.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
			
			<p id="beep" charset="ISO-8859-1"></p>
			<p id="interaction_mesas_setor" charset="ISO-8859-1">SN</p>
		
		
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			<script>
				
				$("#mesas_setor").on("pageshow", function()
					{
						
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}		 
						
							
						$("#mesas_setor #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#mesas_setor #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						var init_request_temp = false;
						var json_mes = "";				
						
						$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,sql : "mesas_setor",par1: JSON.parse(f_storageready("json_par"))[0]["par_horarioverao"],par2: f_storageready("json_messto")}, function(data) 
							{ 
								json_mes = data;
								if( data.length > 0 )
									{  
										var mes_descricao = "";
										var count = "";
										var collapsible = "";
										for(var i = 0, len = data.length; i < len; i++) 
											{						
												var display = data[i]["mov_ver"] == "Ver..." ? "inline" : "none";		
												var vst = data[i]["mov_print"] == "S" ? "checked" : "";
												var pdz = data[i]["mov_produzido"] == "S" ? "checked" : "";
												
												if(data[i]["mes_descricao"] != mes_descricao) 
													{													
														count >= 1 ? collapsible += "</ul></div>" : "";
							
														collapsible += "<div data-role='collapsible' data-inset='false'>" +
																			"<h1>"+data[i]["mes_descricao"]+
																				"<span class='ui-li-count' style='background-color:#ffffff;border:1px solid #dddddd;display:"+display+"'>"+data[i]["mov_ver"]+"</span>" +
																				"<div class='mes_time'>Garçon : "+data[i]["usu_nome"]+ ", Tempo : "+data[i]["mov_tempo"]+" Min </div>" +
																			"</h1>" +												
																			"<ul data-role='listview' data-icon='false' id='list_MesasSetor'>" +
																				"<li>" +
																					"<a href='#' class='ui-btn'>" +
																						"<h1 style='width:230px;text-overflow:ellipsis;overflow:hidden;white-space: nowrap;'>"+ data[i]["mov_qtde"] + " - " + data[i]["pro_descricao"] + "</h1>" +
																						"<p style='font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+" </p>" + 
																					"</a>" +
																					"<div class='right_check'>" +
																						"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_vst' disabled "+vst+" />C</label>" +
																						"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_pdz' "+pdz+" />P</label>" +
																					"</div>" + 
																				"</li>";
														count = count + 1;	
													}	
												else 
													{												
														collapsible +=	"<li>" +
																		"<a href='#' class='ui-btn'>" +
																			"<h1 style='width:230px;text-overflow:ellipsis;overflow:hidden;white-space: nowrap;'>"+ data[i]["mov_qtde"] + " - " + data[i]["pro_descricao"] + "</h1>" +
																			"<p style='font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+" </p>" + 
																		"</a>" +
																		"<div class='right_check'>" +
																			"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_vst' disabled "+vst+"/>C</label>" +
																			"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_pdz' "+pdz+"/>P</label>" +
																		"</div>" + 
																	"</li>";
													}	
													
												mes_descricao = data[i]["mes_descricao"];	
											}
										
										i == len - 1 ? "</ul></div>" : "";
										
										$("#collapsible_MesasSetor").append(collapsible);
										$("div[data-role=collapsible]").collapsible().trigger('create');	
										$(":checkbox").checkboxradio();	
										$("#list_MesasSetor").hasClass('ui-listview') ? $("#list_MesasSetor").listview('refresh') : $("#list_MesasSetor").trigger('create');		
									}	
								else 
									{
										$("#mes_null").css("display", "block");
											
									}
								
								init_request_temp = true;
							});	
						
					
						$("#mesas_setor #menuopcoes").on("click", "li", function()
							{
								if($(this).attr('id') == "Voltar")
									{
										clearInterval(temp_request);
									}
								if($(this).attr('id') == "Atualizar")
									{
										$("#menuopcoes li a ").removeClass("ui-btn-active");
										$(this).find("a").addClass("ui-btn-active");

										$.mobile.loading("show");										
										var wsql = "";
										var wdele = "";	
										$.each($("#mesas_setor .cb_pdz") , function(indice , v)
											{
												var vst_checked = $(this).parents('li').find(".cb_vst").is(":checked") == true ? "S" : "N";
												var pdz_checked = $(this).parents('li').find(".cb_pdz").is(":checked") == true ? "S" : "N";
												
												wsql += wdele + 
													"m" + json_mes[indice]["mes_codigo"] +
													"c" + json_mes[indice]["mov_codigo"] +
													"v" + vst_checked +
													"p" + pdz_checked; 	
													wdele = "©";	
														
											});
										if(wsql == "") 
											{
												f_storagewrite("erro_mensagem" , "Favor checar iten(s), para produzir...." );
												f_storagewrite("page_redirect" , "mesas_setor.php" );
												$ .mobile.changePage ("error.php");
												return true;
											}
										$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action: "f_vistoproduzido",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"],sql: wsql }, function() 
											{
												$("div[data-role=collapsible]").collapsible("collapse");	
												$("#menuopcoes li a ").removeClass("ui-btn-active");
												$("#menuopcoes").popup("close");
												$.mobile.loading("hide");										
											});		 	
																	
										
									}	
							});
						
		
						$('#mesas_setor').on('collapsibleexpand', 'div[data-role=collapsible]', function () {
							init_request_temp = false;
						}).on('collapsiblecollapse', function () {
							init_request_temp = true;
						});
						
						
						var temp_request = setInterval( function()
										{ 
											
											if( init_request_temp == true && $.active == 0 )
												{
													$.mobile.loading("show");
													init_request_temp = false;

													$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action: "f_select",banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] ,	sql : "mesas_setor", par1: JSON.parse(f_storageready("json_par"))[0]["par_horarioverao"], par2: f_storageready("json_messto")}, function(data) 
														{ 
															$("div[data-role=collapsible]").remove();
															if( data.length > 0) 
																{
																	$("#mes_null").css("display", "none");
																	json_mes = data;
																	var mes_request_temp = "";	
																	var wbeep = 0;
																	var tcount = "";
																	var tcollapsible = "";	
																	for(var i = 0, tlen = data.length; i < tlen; i++) 
																		{																											
																			var display = data[i]["mov_ver"] == "Ver..." ? "inline" : "none";		
																			var vst = data[i]["mov_print"] == "S" ? "checked" : "";
																			var pdz = data[i]["mov_produzido"] == "S" ? "checked" : "";
																			wbeep += data[i]["mov_produzido"] == "N" ? 1 : 0;
																			
																			if(data[i]["mes_descricao"] != mes_request_temp) 
																				{													
																					tcount >= 1 ? tcollapsible += "</ul></div>" : "";
														
																					tcollapsible += "<div data-role='collapsible' data-inset='false'>" +
																										"<h1>"+data[i]["mes_descricao"]+
																											"<span class='ui-li-count' style='background-color:#ffffff;border:1px solid #dddddd;display:"+display+"'>"+data[i]["mov_ver"]+"</span>" +
																											"<div class='mes_time'>Garçon : "+data[i]["usu_nome"]+ ", Tempo : "+data[i]["mov_tempo"]+" Min </div>" +
																										"</h1>" +												
																										"<ul data-role='listview' data-icon='false' id='list_MesasSetor'>" +
																											"<li>" +
																												"<a href='#' class='ui-btn'>" +
																													"<h1 style='width:230px;text-overflow:ellipsis;overflow:hidden;white-space: nowrap;'>"+ data[i]["mov_qtde"] + " - " + data[i]["pro_descricao"] + "</h1>" +
																													"<p style='font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+" </p>" + 
																												"</a>" +
																												"<div class='right_check'>" +
																													"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_vst' disabled "+vst+" />C</label>" +
																													"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_pdz' "+pdz+" />P</label>" +
																												"</div>" + 
																											"</li>";
																					tcount = tcount + 1;	
																				}	
																			else 
																				{												
																					tcollapsible +=	"<li>" +
																									"<a href='#' class='ui-btn'>" +
																										"<h1 style='width:230px;text-overflow:ellipsis;overflow:hidden;white-space: nowrap;'>"+ data[i]["mov_qtde"] + " - " + data[i]["pro_descricao"] + "</h1>" +
																										"<p style='font-size:.95em;'>Observação : "+data[i]["mov_observacao"]+" </p>" + 
																									"</a>" +
																									"<div class='right_check'>" +
																										"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_vst' disabled "+vst+"/>C</label>" +
																										"<label style='border:none;padding-top: 0;padding-bottom: 0;'><input type='checkbox' class='cb_pdz' "+pdz+"/>P</label>" +
																									"</div>" + 
																								"</li>";
																				}		
																				
																			mes_request_temp = data[i]["mes_descricao"];					
																		}
																	
																	i == tlen - 1 ? "</ul></div>" : "";
									
																	$("#beep").text( wbeep > 0 ? "beep" : "" );
																	$("#interaction_mesas_setor").text( wbeep > 0 ? "SS action130" : "SN" );
																	
																	$("#collapsible_MesasSetor").append(tcollapsible);
																	$("div[data-role=collapsible]").collapsible().trigger('create');
																	$(":checkbox").checkboxradio();	
																	$("#list_MesasSetor").hasClass('ui-listview') ? $("#list_MesasSetor").listview('refresh') : $("#list_MesasSetor").trigger('create');		
																	$(".ui-navbar #usu").css("background-color", wbeep > 0 ? "#FF9800" : "#373737");
																}		
															else 
																{
																	
																	$("#beep").text("");
																	$("#interaction_mesas_setor").text("SN");
																	$("#mes_null").css("display", "block");
																	$(".ui-navbar #usu").css("background-color", "#373737");
																}
															init_request_temp = true;
															$.mobile.loading("hide");
														}); 	
												}
										} , 30000);
					});
			</script>
			
			
			
        </div>
    </body>
</html>