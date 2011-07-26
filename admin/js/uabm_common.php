
$(document).ready(function() {
   
   
  
   
   
   
    $(".simple_text").editable("?act=partialsave" , { 
        indicator : "<img src='images/indicator.gif'>",
        submit    : 'OK',
        tooltip   : "Click to edit...",
		cssclass  :'inplace_input',
		name	  : 'common_input',
		width:"100%",
		type		: "text",
		submitdata : function() {
           return {fieldName: $(this).attr("fieldName"),recordId: $(this).parents('tr').attr("recordId")}
       } 
    });
	
	 $(".long_text").editable("?act=partialsave&memo=1" , { 
        indicator 	: "<img src='images/indicator.gif'>", 
		type		: "textarea",
		submit    	: 'OK',
        tooltip  	: "Click to edit...",
		cssclass 	: 'inplace_input',
		name		: 'common_input',
		rows 		: 20,
		width		: "100%",
		
		submitdata : function() {
           return {fieldName: $(this).attr("fieldName"),recordId: $(this).parents('tr').attr("recordId")}
       } 
    });
	 
   $(".limit_text").editable("?act=partialsave&memo=1", { 
      indicator : "<img src='images/indicator.gif'>",
      type      : "charcounter",
      submit    : 'OK',
      tooltip   : "Click to edit...",
     
	  cssclass 	: 'inplace_input',
	  name		: 'common_input',
      charcounter : {
         characters : 120
      },
	  submitdata : function() {
           return {fieldName: $(this).attr("fieldName"),recordId: $(this).parents('tr').attr("recordId")}
       } 
 	 });

   



	 $('.file_attach').click(function (e) {
		e.preventDefault();
		$.modal('<iframe name="fileupload" id="fileupload" frameborder="0" src="task_upload_manager.php?act=getManager&task_id='+$(this).attr("recordId")+'" width="100%" height="100%"></iframe>'); 
	});
	 
     $('.truefalse').click(function (e) {
        var objeto = $(this);
       // $(this).html('<img src="images/indicator.gif">');
       
        $.ajax({
                  url: '?act=active&fieldName='+objeto.attr("fieldName")+"&valorAnterior="+objeto.attr("valorAnterior")+"&recordId="+objeto.parents('tr').attr("recordId"),
                  async: true,
                  success:function(htmls){
                                objeto.html(htmls);  
                                if (objeto.attr("valorAnterior")== 1)         
                                    objeto.attr("valorAnterior","0");
                                else     
                                    objeto.attr("valorAnterior","1");                                
                                },
                  beforeSend:function (){objeto.html('<img src="images/indicator.gif">');  }            
                 });
      
                 
     }); 
      
	  
	 $('.html_attach').click(function (e) {
		e.preventDefault();
		// load the contact form using ajax
		//<iframe name="fileupload" id="fileupload" frameborder="0" src="fileupload.php"></iframe>
		$.modal('<iframe name="htmlupload" id="htmlupload" frameborder="0" src="html_editor.php?act=getManager&task_id='+$(this).attr("recordId")+'" width="100%" height="100%"></iframe>'); 
	});
	 
	 
	 
	 
	 $(".priority").editable("?act=partialsave", { 
			data   : "{'1':'1','2':'2','3':'3','4':'4','5':'5', 'selected':'1'}",
			type   : "select",
			submit : "OK",
			name		: 'common_input',
			cssclass 	: 'inplace_input',
			submitdata : function() {
           return {fieldName: $(this).attr("fieldName"),recordId: $(this).parents('tr').attr("recordId")}
       } 
		});
	
	 
	
});