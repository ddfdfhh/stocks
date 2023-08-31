var loaderRef=$("#loader");/**Id of icon div embeded in submit buttons  */
$.validator.addMethod("pwcheck", function(value) {
    return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) // consists of only these
        && /[a-z]/.test(value) // has a lowercase letter
        && /\d/.test(value) // has a digit
 });
 $.validator.addMethod("phone", function(value) {
    return /^\d{10,}$/.test(value) 
      
 });
 $.validator.addMethod("zip", function(value) {
    return /^\d{4,6}$/.test(value)
      
 });
 /**Change form validation default error messages  */
 $.extend( $.validator.messages, {
    required: "This field is required.",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    equalTo: "Please enter the same password again.",
    maxlength: $.validator.format( "Please enter no more than {0} characters." ),
    minlength: $.validator.format( "Please enter at least {0} characters." ),
    rangelength: $.validator.format( "Please enter a value between {0} and {1} characters long." ),
    range: $.validator.format( "Please enter a value between {0} and {1}." ),
    max: $.validator.format( "Please enter a value less than or equal to {0}." ),
    min: $.validator.format( "Please enter a value greater than or equal to {0}." ),
    step: $.validator.format( "Please enter a multiple of {0}." )
  } );
function blockUi(){
  let loading_msg="";
  let options={
    showOverlay: true, 
    overlayCSS:  { 
      backgroundColor: '#000', 
      opacity:         0.6, 
      cursor:          'wait' 
  },
  css: { 
    padding:        0, 
    margin:         0, 
    width:          '30%', 
    top:            '40%', 
    left:           '35%', 
    textAlign:      'center', 
    color:          '#000', 
    border:         'none', 
    backgroundColor:'transparent', 
    cursor:         'wait' 
},
  message:"<div class='spinner-border'></div>"
  }
  if(typeof $.blockUI!=="undefined")
      $.blockUI(options);
}
function unBlockUi(){
  if(typeof $.unblockUI!=="undefined")
$.unblockUI();
}
 
function successAlert(msg)
{
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: msg
      
      })
  // iziToast.success({
  //   title: 'Great!',
  //   message: msg,
  //   position: 'topRight'
  // });
  
}

function errorAlert(error='')
{
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: (error.length>0)?error:'Something went wrong!'
       
      })
  // iziToast.error({
  //   title: 'Oops!',
  //   message:(error.length>0)?error:'Something went wrong!',
  //   position: 'topRight'
  // });
 
}
/***==================================================Form validation template ========================================= */
  //Without image
  function formValidateFunctionTemplate(rules,messages={},btnid,formid,url,callbackSuccess=undefined,callbackError=undefined){

    $("#"+formid).validate({
      
     rules,
       messages,
       focusCleanup: true,
       submitHandler:function(form,event){
        event.preventDefault();
           formid=$(form).attr('id')
           formAjaxSubmitWithServerValidationError(btnid,formid,url,callbackSuccess,callbackError)
       }
     });
   
  }
  function formValidateFunctionTemplateLogin(rules,messages={},btnid,formid,url,callbackSuccess=undefined,callbackError=undefined){

    $("#"+formid).validate({
      
     rules,
       messages,
       focusCleanup: true,
       submitHandler:function(form,event){
        event.preventDefault();
           formid=$(form).attr('id')
           formAjaxSubmitWithServerValidationErrorLogin(btnid,formid,url,callbackSuccess,callbackError)
       }
     });
   
  }
    //With image upload (multiple or single both works) 
  function formValidateFunctionTemplateImage(rules,messages={},btnid,formid,url,callbackSuccess=undefined,callbackError=undefined){
  console.log(formid);
    $("#"+formid).validate({
      onfocusout:function(el,ev){
        if(!$(el).valid()) 
           {
              $(el).removeClass('is-valid');
               $(el).addClass('is-invalid');
               $(el).val(' '); $(el).focus();
    
           }
        else{
            $(el).addClass('is-valid');
            $(el).removeClass('is-invalid');
        }
    } ,
       rules,
       messages,
       focusCleanup: true,
       onkeydown: function (element) {
        $(element).valid();
        },
        submitHandler:function(form){
           formid=$(form).attr('id')
           formAjaxSubmitWithImageWithServerValidationError(btnid,formid,url,callbackSuccess,callbackError)
       }
     });
   
  }
  /*********==================================Template  ends ========================================= */
    /**==============================================Form Ajax Submission With server validation Only  ============= */
    //Without Image
  function formAjaxSubmitWithServerValidationError(btnid,formid,url,callbackSuccess=undefined,callbackError=undefined)
    {   
      let  btn=$("#"+btnid);
       let formData=$("#"+formid).serialize();
       
       $.ajax({
          url:url,
           method:"POST",
           dataType:'json',
           data:formData,
          
           beforeSend:function(){
             btn.html('Please wait..')
             disableBtn(btn);
           },
           success:function(res,textStatus, xhr){
             enableBtn(btn);
             
             if(res['success'] && xhr.status===200)
              {
                console.log('okok');
                  $("#"+formid).trigger('reset');
                  successAlert(res['message']);
                  if(callbackSuccess)
                  callbackSuccess(res);
              }
              else
              {
                loaderRef.hide();
                  // $('.alert-danger').show();
                  // $('.alert-danger').html(res['message']);
                  errorAlert(res['message']);
                  if(callbackError)
                     callbackError(res);
              }
            
           },
           complete:function(){
             enableBtn(btn);
           },
           error: function (xhr, status, errorThrown) {
           
                  enableBtn(btn);
                  callbackError(errorThrown);
                  //formatErrorMessage(xhr,errorThrown);
                  /*****Show validation errors if any in a top div */
                //   $('#validation-errors').html('');
                //   $.each(xhr.responseJSON.errors, function(key,value) {
                //     $('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                // }); 
                 /*****Show validation errors in taostr from */
                
                 $.each(xhr.responseJSON.errors, function(key,value) {
                   errorAlert(value);
               }); 
           }
  
  
  
  
      });
    }
  function formAjaxSubmitWithServerValidationErrorLogin(btnid,formid,url,callbackSuccess=undefined,callbackError=undefined)
    {   
      let  btn=$("#"+btnid);
       let formData=$("#"+formid).serialize();
       
       $.ajax({
          url:url,
           method:"POST",
           dataType:'json',
           data:formData,
          
           beforeSend:function(){
             btn.html('Please wait..')
             disableBtn(btn);
           },
           success:function(res,textStatus, xhr){
             enableBtn(btn);
             
             if(res['success'] && xhr.status===200)
              {
              
                  $("#"+formid).trigger('reset');
                
                  if(callbackSuccess)
                  callbackSuccess(res);
              }
              else
              {
               
                  if(callbackError)
                     callbackError(res);
              }
            
           },
          
           error: function (xhr, status, errorThrown) {
            enableBtn(btn);
                
                 
                 $.each(xhr.responseJSON.errors, function(key,value) {
                  callbackError(value);
               }); 
           }
  
  
  
  
      });
    }
      //With Image
    function formAjaxSubmitWithImageWithServerValidationError(btnid,formid,url,callbackSuccess=undefined,callbackError=undefined)
    {   
      let  btn=$("#"+btnid);
      let  formData=new FormData(document.getElementById(formid));
     // formData.append('file', $('#image')[0].files[0]);
        
       $.ajax({
          url:url,
           method:"POST",
           dataType:'json',
           data:formData,
           processData:false,
           contentType:false,
           cache:false,
          
           beforeSend:function(){
             disableBtn(btn);
           },
           success:function(res,textStatus, xhr){
             enableBtn(btn);
             
             if(res['success'] && xhr.status===200)
              {
                  $("#"+formid).trigger('reset');
                  successAlert(res['message']);

                  if(callbackSuccess)
                  callbackSuccess(res);
              }
              else
              {
                loaderRef.hide();
                  // $('.alert-danger').show();
                  // $('.alert-danger').html(res['message']);
                  errorAlert(res['message']);
                  if(callbackError)
                     callbackError(res);
              }
            
           },
           complete:function(){
             enableBtn(btn);
           },
           error: function (xhr, status, errorThrown) {
                  enableBtn(btn);
                  //formatErrorMessage(xhr,errorThrown);
                  /*****Show validation errors if any in a top div */
                //   $('#validation-errors').html('');
                //   $.each(xhr.responseJSON.errors, function(key,value) {
                //     $('#validation-errors').append('<div class="alert alert-danger">'+value+'</div');
                // }); 
                 /*****Show validation errors in taostr from */
                
                 $.each(xhr.responseJSON.errors, function(key,value) {
                   errorAlert(value);
               }); 
           }
  
  
  
  
      });
    }
    /**===================================Onyl Ajax submit no validation =============================================== */
    function formatErrorMessage(jqXHR, exception) {

        if (jqXHR.status === 0) {
            errorAlert('Not connected.\nPlease verify your network connection.');
        } else if (jqXHR.status == 404) {
            errorAlert('The requested page not found. [404]');
        } else if (jqXHR.status == 500) {
        } else if (jqXHR.status == 403) {
            errorAlert('Please refresh page.');
        } else if (jqXHR.status == 500) {
            errorAlert('Internal Server Error [500].');
        } else if (exception === 'parsererror') {
            errorAlert('Requested JSON parse failed.');
        } else if (exception === 'timeout') {
            errorAlert('Time out error.');
        } else if (exception === 'abort') {
            errorAlert('Request aborted.');
        } else {
            errorAlert('Uncaught Error.\n' + jqXHR.responseText);
        }
      }
      function disableBtn(btn){
     
        btn.prop('disabled',true);
        btn.css('opacity','0.7');
        // loaderRef.css('display','inline-block');
       blockUi();
      }
      function enableBtn(btn){
        
        btn.css('opacity','1');
        btn.prop('disabled',false);
        // loaderRef.css('display','none');
        if(typeof  $.unblockUI!=="undefined")
        $.unblockUI();
      }
      /**==============Form Ajax submit with loading icon on button  =================================*/
      function formAjaxWithBtnAndLoader(btnid,formid,url,callbackSuccess=undefined,calbackError=undefined)
      {   
         let  btn=$("#"+btnid);
         let  form=$("#"+formid);
         let  formData=$("#"+formid).serialize();
          
         $.ajax({
            url:url,
             method:"POST",
             dataType:'json',
             data:formData,
           beforeSend:function(){
               disableBtn(btn);
             },
             success:function(res, textStatus, xhr){
               enableBtn(btn);
               if(res['success'] && xhr.status===200)
                {
                    $("#"+formid).trigger('reset');
                    successAlert(res['message']);
                    if(callbackSuccess)
                    callbackSuccess(res);
                }
                else
                {
                  loaderRef.hide();
                    // $('.alert-danger').show();
                    // $('.alert-danger').html(res['message']);
                    errorAlert(res['message']);
                    if(callbackError)
                       callbackError(res);
                }
              
             },
             complete:function(){
               enableBtn(btn);
             },
             error: function (xhr, status, errorThrown) {
                    enableBtn(btn);
                    formatErrorMessage(xhr,errorThrown);
             }
    
    
    
    
        });
      }
      /**==============Form with Image Ajax submit with loading icon on button  =================================*/
      function formAjaxImageWithBtnAndLoader(btnid,formid,url,callbackSuccess=undefined,calbackError=undefined)
      {   
       let  btn=$("#"+btnid);
        
       let form=$("#"+formid);
      let  formData=new FormData(document.getElementById(formid));
        formData.append('file', $('#image')[0].files[0]);
         
         $.ajax({
            url:url,
             method:"POST",
             dataType:'json',data:formData,
             processData:false,
             contentType:false,
             cache:false,
             async:true,
             beforeSend:function(){
               disableBtn(btn);
             },
             success:function(res, textStatus, xhr){
               enableBtn(btn);
               if(res['success'] && xhr.status===200)
                {
                    $("#"+formid).trigger('reset');
                    successAlert(res['message']);
                    if(callbackSuccess)
                    callbackSuccess(res);
                }
                else
                {
                  loaderRef.hide();
                    // $('.alert-danger').show();
                    // $('.alert-danger').html(res['message']);
                    errorAlert(res['message']);
                    if(callbackError)
                    callbackError(res);
                }
              
             },
             complete:function(){
               enableBtn(btn);
             },
             error: function (xhr, status, errorThrown) {
                    enableBtn(btn);
                    formatErrorMessage(xhr,errorThrown);
             }
    
    
    
    
        });
      }
      /**==============Objec Data  Ajax send with loading icon on button  =================================*/
        function objectAjaxWithBtnAndLoader(btnid=undefined,object,url,callbackSuccess=undefined,calbackError=undefined)
      { 
         let  btn=$("#"+btnid);
        
         let  formData=object;
          $.ajax({
            url:url,
             method:"POST",
             dataType:'json',
             data:formData,
            
             beforeSend:function(){
               disableBtn(btn);
             },
             success:function(res, textStatus, xhr){
               enableBtn(btn);
               if(res['success'] && xhr.status===200)
                {
                   
                    successAlert(res['message']);
                    if(callbackSuccess)
                      callbackSuccess(res);
                }
                else 
                {
                 
                    loaderRef.hide();
                    // $('.alert-danger').show();
                    // $('.alert-danger').html(res['message']);
                    errorAlert(res['message']);
                    if(callbackError)
                       callbackError(res);
                }
              
             },
             complete:function(){
               enableBtn(btn);
             },
             error: function (xhr, status, errorThrown) {
                    enableBtn(btn);
                    formatErrorMessage(xhr,errorThrown);
             }
         
        
    
        });
      }
       /**==============Objec Data  Ajax send no loading icon no alert  =================================*/
      function objectAjaxNoLoaderNoAlert(object,url,callbackSuccess=undefined,calbackError=undefined)
      { 
       
          let formData=object;
          $.ajax({
            url:url,
             method:"POST",
             dataType:'json',
             data:formData,
            
             success:function(res, textStatus, xhr){
             
              if(res['success'] && xhr.status===200)
                {
                   if(callbackSuccess)
                      callbackSuccess(res);
                }
                else 
                {
                   
                    if(callbackError)
                       callbackError(res);
                }
              
             },
          
             error: function (xhr, status, errorThrown) {
                   
                    formatErrorMessage(xhr,errorThrown);
             }
         
        
    
        });
      }