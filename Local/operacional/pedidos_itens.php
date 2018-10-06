<!DOCTYPE html><html>    <head>        <title>Maitre | Operacional</title>    				<meta charset="utf-8">		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />		<meta name="viewport" content="width=device-width, initial-scale=1">		    </head>    <body>           <body>        <div data-role="page" id="pedidos_itens">                         <div data-role="header">							<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>                				<h1>Pedidos</h1>				<div data-role="navbar">					<ul>						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 					</ul>				</div>								<ul data-role="listview" id="list_Title" >					<li  data-role="list-divider" id="title"> 											</li>				</ul>				            </div>             			 			            <div data-role="main" class="ui-content">								<div data-role="collapsibleset" data-inset="false" id="collapsible_PedidosItens"></div>								<div id="resumo">					<ul data-role="listview" data-icon="false">					</ul>				</div>            </div>          			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >				<ul data-role="listview" data-inset="true"  >					<li  data-role="list-divider"> Opções </li>					<li id="Limpar" data-icon="recycle" > <a href="#"> Limpar </a> </li>					<li id="Resumo" data-icon="bars"> <a href="#" > Resumo  </a> </li>					<li id="Refazer" data-icon="back"> <a href="#"> Refazer </a> </li>					<li id="Gravar" data-icon="check"> <a href="#"> Gravar </a>  </li>					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>				</ul>			</div>						<div data-role="footer" data-position="fixed">                <h1>Maitre | Operacional</h1>            </div>						<script>				$("#pedidos_itens").on("pageshow", function()					{							if(f_storagecheck("mob") == false) 							{								location.href="notfound.php";								return true;								}		 										$("#pedidos_itens #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );						$("#pedidos_itens #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );												var json_mesaseq = {action: "f_select", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"] , sql: "mov_maxcod", par1: f_storageready('mes_codigo') }; 						var json_pro = JSON.parse(f_storageready("json_pro"));						$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', json_mesaseq , function(data) 							{								json_mesaseq = data;								//$("#pedidos_itens #list_PedidosItens li").remove();								$("#pedidos_itens #title").html(f_storageready('mes_descricao') + " | " + f_storageready("wmes_aberta") + "<span class='ui-li-count' id='totalGeral' ></span>");								if(f_storagecheck("cardapio") == false) 									{ 										var gru_descricao = "";										var count = 0;										var collapsible = "";										for(var i = 0, len = json_pro.length; i < len; i++) 											{													if(json_pro[i]["gru_codigo"] != gru_descricao) 													{																											count >= 1 ? collapsible += "</ul></div>" : "";																												collapsible += "<div data-role='collapsible' data-inset='false'>" +																		"<h1>"+json_pro[i]["gru_descricao"]+"</h1>" +																														"<ul data-role='listview' data-icon='false' id='list_PedidosItens' class='has_right_check'>" +																																								"<li>" +																				"<a href='#'>" +																					"<input type='number' data-role='none'/>" + 																					"<h1> " +json_pro[i]["pro_descricao"]+ " </h1>" +																					"<textarea></textarea>" +																					"<span class='ui-li-count'>" +																						"<span class='valor_Digitado'></span>" +																						"<span class='valor_Unitario'>" + json_pro[i]["pro_valor"] + "</span>" +																						"<span class='valor_Total'></span>" + 																					"</span>" +																					"<span class='pro_unidade'>"+json_pro[i]["pro_unidade"]+"</span>" + 																				"</a>" +																			"</li>";																														count = count + 1;															}													else 													{																										collapsible += "<li>" +																		"<a href='#'>" +																			"<input type='number' data-role='none' />" + 																			"<h1>  " +json_pro[i]["pro_descricao"]+ " </h1>" +																			"<textarea></textarea>" +																			"<span class='ui-li-count'>" +																				"<span class='valor_Digitado'></span>" +																				"<span class='valor_Unitario'>" + json_pro[i]["pro_valor"] + "</span>" +																				"<span class='valor_Total'></span>" + 																			"</span>" +																			"<span class='pro_unidade'>"+json_pro[i]["pro_unidade"]+"</span>" + 																			"</a>" +																	"</li>";													}														gru_descricao = json_pro[i]["gru_codigo"];												}										i == len - 1 ? "</ul></div>" : "";										f_storagewrite("cardapio" , collapsible );									}																									else 									{									  									  $("#collapsible_PedidosItens").append(f_storageready("cardapio"));									}																	$("#collapsible_PedidosItens").append(collapsible);								$("div[data-role=collapsible]").collapsible().trigger('create');								$("#list_Title").listview('refresh');																		});																	$('div[data-role=collapsibleset]').on('keyup input', 'input[type=number]', function () 							{								var total = "";								var totalgeral = 0;																total = $(this).val() == "" ? 0 : $(this).parents("li").find(".valor_Unitario").text() * (  $(this).parents("li").find(".pro_unidade").text() == "u" ? $(this).val() : $(this).val() / 1000) ;								total == 0 ?  $(this).parents("li").find(".valor_Digitado").text("") : $(this).parents("li").find(".valor_Digitado").text($(this).val() + " x ") ;											total == 0 ?  $(this).parents("li").find(".valor_Total").text("") : $(this).parents("li").find(".valor_Total").text( " = " + total.toFixed(2)) ;																			$.each( $("div[data-role=collapsibleset] .valor_Total:contains(=)") , function()									{										totalgeral = totalgeral + parseFloat($(this).text().replace("=" , ""));									});								$("#pedidos_itens #totalGeral").text("R$ " + totalgeral.toFixed(2) );  									totalgeral == 0 ? $("#pedidos_itens #totalGeral").css("display", "none") : $("#pedidos_itens #totalGeral").css("display", "inline"); 														});												$("#pedidos_itens #menuopcoes").on("click", "li", function(e, event)	 							{								if($(this).attr("id") == "Resumo")									{										$("#resumo ul li").remove();										$("div[data-role=collapsibleset]").css("display", "none");											$("#resumo").css("display" , "block");										var resumoul = "";										$.each($("input[type=number]"), function(i,v)											{ 												if( $(this).val() != "" )													{														resumoul += "<li>" +																		"<a href='#' style='padding:20px;'>" +																			"<h1>" +$(this).val()+ " - " + json_pro[i]["pro_descricao"]+"</h1>" +																			"<p style='font-size:.95em;display:block;'>Observação : "+$(this).siblings("textarea").val()+"</p>" +																			"<span class='ui-li-count' style='font-size:.88em;font-weight:700;top:85px;'>" + json_pro[i]["pro_valor"] + "</span>" +																		"</a>" +																	"</li>" 													}											});																			$("#resumo ul").append(resumoul).listview('refresh');											$("#pedidos_itens #menuopcoes").popup("close");									}								if($(this).attr("id") == "Refazer") 									{										$("#resumo").css("display" , "none");										$("div[data-role=collapsibleset]").css("display", "block");											$("#pedidos_itens #menuopcoes").popup("close");																			}								if($(this).attr("id") == "Limpar") 									{										$("div[data-role=collapsibleset]").css("display", "block");											$("#pedidos_itens #totalGeral , #resumo").css("display", "none");										$(".valor_Digitado, .valor_Total").text("");										$(":input").val("");										$("#pedidos_itens #menuopcoes").popup("close")									}									if($(this).attr("id") == "Gravar") 													{																				$("#menuopcoes li a ").removeClass("ui-btn-active");										$(this).find("a").addClass("ui-btn-active");										$.mobile.loading("show");																														var wsql = "";																var wdele = "";										var wpro = {};										var wpro_array = [{}];										var seq = parseInt(json_mesaseq[0]["mov_codigo"]);										$.each($("div[data-role=collapsibleset] input[type=number]"), function(indice, value)											{																					if($(this).val() != "") 														{																				var ii = json_pro[indice].pro_unidade == "u" ? $(this).val() : 1;															for(var i = 1; i <= ii; i++ )																{																							var qtde =  json_pro[indice].pro_unidade == "u" ? 1 : $(this).val() / 1000;																																		wsql +=  wdele + 																																										ElementsAspa(json_pro[indice].pro_codigo) + "," +																		qtde + "," +																											json_pro[indice].pro_valor + "," +																ElementsAspa( $(this).parents("li").find("textarea").val() ) + "," +																ElementsAspa(json_pro[indice].pro_visto) + "," +																ElementsAspa(json_pro[indice].pro_produzido) + ","; 																wdele = "©";																																if(json_pro[indice]["pro_comanda"] == "S") 																	{																		seq = seq + 1;																		wpro["mov_codigo"] = seq;																		wpro["mes_codigo"] = f_storageready('mes_codigo');																		wpro["mov_qtde"] = qtde.toFixed(3);																		wpro["sto_codigo"] =  json_pro[indice]["sto_codigo"];																		wpro["sto_descricao"] =  json_pro[indice]["sto_descricao"];																		wpro["pro_descricao"] =  json_pro[indice]["pro_descricao"];																		wpro["usu_nome"] = JSON.parse(f_storageready("json_usu"))[0]["usu_nome"];																		wpro["mov_observacao"] =  $(this).parents("li").find("textarea").val();																		wpro["mov_produzido"] = json_pro[indice]["pro_produzido"];																		wpro_array.push(  wpro );																		wpro = {};																	}															}													}																																			});													if(wsql == "") 											{												f_storagewrite("erro_mensagem" , "Favor digitar quantidades, para pedido(s)...." );												f_storagewrite("page_redirect" , "pedidos_itens.php" );												$ .mobile.changePage ("error.php");												return true;											}										var wwdados = {action: "f_insertpedidos", banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"], ter : ElementsAspa(JSON.parse( f_storageready("json_ter") )[0]["ter_codigo"]), usu : ElementsAspa(JSON.parse(f_storageready("json_usu"))[0]["usu_nome"]), mes : f_storageready("mes_codigo"),sql: wsql};										$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', wwdados, function(data) 											{												if(wpro_array.length > 1) 													{														$.getJSON('http://192.168.0.7/maitre/processa_operacional.php?callback=?', {action: "f_datahora"}, function(data) 															{																wpro_array.shift();																		wpro_array.sort(sort_by('sto_codigo', false, 0));																																f_storagewrite("json_dados", JSON.stringify(wpro_array) );																f_storagewrite("rel", "cda");																f_storagewrite("interaction", "SS action108");																f_storagewrite("fil", "* " + f_storageready("mes_descricao").toUpperCase() + " * " + data.DataHora + " " );																$.mobile.changePage('menu_impressoras.php');																		});																}												else 													{														$.mobile.changePage("menu_principal.php");														}												});												 																			}															});													$("input[type=number]").on('keypress', function(e, event)							{								if( /[0123456789]/.test(String.fromCharCode(e.keyCode)) ) 									{ 										return true;									}								return false;							});							$('input[type=number] , textarea').keypress(function(e)							{								if ( e.keyCode == 13 )									{										return false;									} 							});							});			</script>									        </div>				</body></html>