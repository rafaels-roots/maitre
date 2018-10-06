<!DOCTYPE html>
<html lang="pt-br" >
    <head>
        <title>Maitre | Operacional</title>    
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script src="jquery.js"></script>
		<script src="jquerymobile.js"></script>
		<link rel="stylesheet" href="jqmcss.css">
	</head>
    <body>
   
        <div data-role="page" id="index">  
           
            <div data-role="header" data-position="fixed">
                <h1>Terminal</h1>
            </div> 
            
            <div data-role="main" class="ui-content">
				
				<div class="infoOperacional">	
					
					<p style="text-align:center;"> <span style="font-size:26px;font-weight:bold;"> Elohim Systems </span>  </p>				
					<p style="text-align:center;padding:0;margin-top:-15px;font-size:14px;"> <span class="labelwhats">WhatsApp : </span> <span id="whats"> 55 85 99189-5141</span> </p>
					<p style="text-align:center;padding:0;margin-top:-15px;font-size:14px;"> <span class="labelemail">Email :</span> <span id="email">elohimsystems@hotmail.com</span> </p>
				</div>
				
                <form method="post">   
                    
                    <label for="mac_ter">Terminal : Verificando Mobilidade... ! </label>
                    <input type="text" name="mac_ter" id="mac_ter" value="F4-8E-38-DF-E5-92" data-wrapper-class="ui-custom"  />
                    
                    <input type="button" id="mac_submit" value="Validar" style="text-shadow:none;margin-top:10px;">	  
                </form>
            </div>
			
			<p id="useragent">android</p>
			<p id="interaction_index">SS action101</p>
			
			
            <div data-role="footer" data-position="fixed">
                <h1>Maitre | Operacional</h1>
            </div>
			
			<script>
					$("#index").on("pageshow", function() 
						{ 	
							var userAgent = setInterval( function()
								{ 
									if($("#useragent").text().indexOf("android") >= 0) 
										{
											clearInterval(userAgent);
											$("label[for=mac_ter]").text("Terminal :")
											$("#index .ui-input-btn , #index .ui-input-text").css("display", "block");
											f_storagewrite("mob", "");
										}
								} , 1000);

							$("#mac_submit").click(function()
								{
									$.mobile.loading("show");																				
									$.getJSON('http://www.sisleq.com.br/maitre/operacional/processa_operacional.php?callback=?', {action: "f_select", banco: "sisle873_maitre_terminal", sql : "terminal", par1 :  $("#mac_ter").val() }, function(data)
										{
											if(data.length == 0  )
												{  
													f_storagewrite("erro_mensagem" , "Terminal não cadastrado ou inativo..." );
													f_storagewrite("page_redirect" , "index.php" );
													$ .mobile.changePage ("error.php");
												} 
											else 
												{						 		
													f_storagewrite("json_ter" , JSON.stringify(data));
													$ .mobile.changePage ("empresa.php");	
												}
										});
										
								});
						}); 
		
			</script>
	
	</div>  
		
		
</body>

</html>
