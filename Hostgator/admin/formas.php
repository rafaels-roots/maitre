<!DOCTYPE html>
<html>
    <head>
        <title>Maitre | Admin</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
    </head>
    <body>
        
        <div data-role="page" id="formas">  
           
            <div data-role="header">
				<a href="#menuopcoes" style="border-radius:1.5em !important;" class="ui-btn-right ui-btn ui-btn-b ui-btn-inline ui-mini ui-corner-all ui-btn-icon-left ui-icon-bars" id="#menu" data-rel="popup" data-position-to="#menu">Menu</a>
                
                <h1>Formas</h1>
				<div data-role="navbar">
					<ul>
						<li><button data-icon="home" data-theme="b" id="emp">...</button></li>
						<li><button data-icon="user" data-theme="b" id="usu">...</button></li> 
					</ul>
				</div>
            </div> 
            
			 
			
            <div data-role="main" class="ui-content" >
			
				<ul data-role="listview" data-icon="false" id="list_Formas">
					
				</ul>
				
            </div>
			 
			
			<div data-role="popup" id="menuopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Voltar" data-icon="back"> <a href="menu_principal.php"> Voltar </a> </li>
				</ul>
			</div>
			
			
			<div data-role="popup" id="formaopcoes" style="min-width:220px;" >
				<ul data-role="listview" data-inset="true"  >
					<li  data-role="list-divider"> Opções </li>
					<li id="Incluir" data-icon="plus"> <a href="crud_formas.php"> Incluir </a> </li>
					<li id="Alterar" data-icon="recycle"> <a href="crud_formas.php"> Alterar </a> </li>
					<li id="Excluir" data-icon="minus"> <a href="crud_formas.php"> Excluir </a> </li>
					<li id="Voltar" data-icon="back"> <a href="#"> Voltar </a> </li>
					
				</ul>
			</div>
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Admin</h1>
            </div>
			
			<script>
				$("#formas").on("pageshow", function() 
					{
						if(f_storagecheck("mob") == false) 
							{
								location.href="notfound.php";
								return true;	
							}
						
						
						$("#formas #usu").text( JSON.parse( f_storageready("json_usu") )[0]["usu_nome"] );
						$("#formas #emp").text( JSON.parse(f_storageready("json_emp"))[0]["emp_descricao"] );
						
						
						$.getJSON('http://www.sisleq.com.br/maitre/admin/fpg_processa.php?callback=?', {action: "f_select",	banco : JSON.parse(f_storageready("json_ter"))[0]["emp_banco"]}, function(data)
							{
								f_storagewrite("json_fpg", JSON.stringify(data) );
								
								if( data.length == 0 )
									{  
										f_storagewrite("sql_statments" ,  "Incluir" );
										$ .mobile.changePage ("crud_formas.php");
										return true;						
									}
								else 
									{
										$("#list_Formas li").remove();
										var list_Formas = "";
										for(var i = 0, len = data.length; i < len; i++) 
											{	
												list_Formas += "<li>" +
																	"<a href='#formaopcoes' data-rel='popup' data-position-to='window'>" +
																		"<span id='fpg_DesValue'>"+data[i]["fpg_descricao"]+"</span>"  +
																	"</a>" +
																"</li>"
											}
										$("#list_Formas").append(list_Formas).listview("refresh");
	
									}
							});	
					
						
							
						
						$("#list_Formas").on( "click", "li", function() 
							{
								var json_fpg = JSON.parse(f_storageready("json_fpg"));
								var json_fpgclick = "[" + JSON.stringify( json_fpg[$(this).index()] ) + "]" ; 
								f_storagewrite("json_fpgclick", json_fpgclick);
							});
							
							
							
						$("#formaopcoes ul").on( "click", "li", function() 
							{
								if($(this).attr('id') == "Voltar") 
									{
										$("#formaopcoes").popup("close");
										return false;							
									}
						
								f_storagewrite("sql_statments" , $(this).attr('id') );
							});
					
					});
			</script>
			
			
        </div>
    </body>
</html>