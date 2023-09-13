/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 * 
 Tips
 1-Select with search with hightlight use https://www.jqueryscript.net/demo/autocomplete-styled-bootstrap/
 2-FIle upload only input    -jquery.filrer.js has add more funcitnality also but not styled like product add more
      $("#file_input").filer( Obj );
  3-For combining input with file upload you can use dropzone js
  4-Live password strength checker -https://www.jqueryscript.net/demo/check-strength-password/
     password-strength.js 
  5-For bootstrpa compatible validation inline icon with error and success use strict 
  ttps://www.jqueryscript.net/demo/bootstrap-compatible-validator/
  6-Image upload with add more options
      https://www.jqueryscript.net/demo/multi-image-uploader-bootstrap/
 7-To have multiple  tag based input 
       https://www.jqueryscript.net/form/Tiny-Text-Field-Based-Tags-Input-Plugin-For-jQuery-Tagify.html
8-TO have select with search and with tagged form use even live ajax FastSelect best plugin 
9-best range slider https://www.jqueryscript.net/form/Highly-Customizable-Range-Slider-Plugin-For-Bootstrap-Bootstrap-Slider.html
   bootstrap-slider.js
10-Ecommerce image zoom cloud zoom plugin 
    
     
 */

/****************Image preview onload ***************************/
function singleImagePreview(input, placeToInsertImagePreview) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        let file = input.files[0];
        if (file["type"].search("image") >= 0) {
            reader.onload = function (event) {
                let y = event.target.result;
                $("#" + placeToInsertImagePreview).append(
                    `<img src='${y}'  class='img_rounded' />`
                );
            };

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }
}
/**======================================Multiple Image Preview js===================  */
var multiImagePreview = function (input, placeToInsertImagePreview) {
    if (input.files) {
        var filesAmount = input.files.length;
        for (i = 0; i < filesAmount; i++) {
            let file = input.files[i];
            if (file["type"].search("image") >= 0) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    let y = event.target.result;
                    $("#" + placeToInsertImagePreview).append(
                        `<img src='${y}'  class='img_rounded' style='width:100px;height:100px;margin:5px' />`
                    );
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
};
/**=================Get interdependednt Select Box Data=========================== */
function showDependentSelectBox(
    dependee_key,
    dependent_key,
    value,
    dependent_select_box_id,
    table,
    table_id = "id",
    callback
) {
    if (value.length == 0) {
        return false;
    }
    let obj = { dependee_key, dependent_key, value, table, table_id };
    var callbackSuccess = function (response) {
        $("select#" + dependent_select_box_id).html(response["message"]);
        callback();
    };
    objectAjaxNoLoaderNoAlert(obj, "/getDependentSelectData", callbackSuccess);
}
function showDependentSelectBoxForMultiSelect(
    dependee_key,
    dependent_key,
    value = [],
    dependent_select_box_id,
    table,
    table_id = "id",
    callback
) {
    /**this funcyion is for multiple value select  */
    if (value.length == 0) {
        return false;
    }
    let obj = {
        dependee_key,
        dependent_key,
        value: JSON.stringify(value),
        table,
        table_id,
    };
    var callbackSuccess = function (response) {
        $("select#" + dependent_select_box_id).html(response["message"]);
        callback();
    };
    objectAjaxNoLoaderNoAlert(
        obj,
        "/getDependentSelectDataMultipleVal",
        callbackSuccess
    );
}
function dynamicAddRemoveInputBox(
    container_id,
    todo,
    key,
    input_id_to_remove = undefined
) {
    str = "#" + container_id + " >input";
    current_Count = $(str).length;
    new_html_id = current_child_Count + 1;
    html = `<input type='text' name='${key}' id='inp-${new_html_id}'   />`;
    if ((todo = "append")) $("#" + container_id).append(html);
    if ((todo = "remove" && input_id_to_remove))
        $("#" + container_id + " " + "#" + input_id_to_remove).remove();
}
function liveSearchSelect(selectboxid, url) {
    $("#" + selectboxid).autocomplete({
        source: function (request, response) {
            var ajaxOpt = { url: url, data: { term: request.term } };
            $.ajax(ajaxOpt).done(function (data) {
                response(
                    data
                ); /**==========Response(data )shold be array with item {value:'v1', label:'Value 1',extradata:'jQuery1'} ====******/
            });
        },
    });
}
function multiSelectCheckBoxAction(value, field, url, table) {
    /******field coulumn whose value is to be set to value **/
    Swal.fire({
        title: "Are you sure want to set it " + value + "?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let p = new Array();
            $(`input[name='ids[]']:checked`).each(function (i) {
                p.push($(this).val());
            });
            obj = { ids: JSON.stringify(p), field, value, table };
            let callbackSuccess = function () {
                for (i of p) {
                    $("#row-" + i).hide();
                }
                location.reload();
            };
            objectAjaxWithBtnAndLoader("btnid", obj, url, callbackSuccess);
        }
    });
}
function assignRowsToSomeUser(selectboxid, set_in_table, field_to_set) {
    /******field coulumn whose value is to be set to value **/
    Swal.fire({
        title: "Are you sure want to assign user?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, do it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let p = new Array();
            $(`input[name='ids[]']:checked`).each(function (i) {
                p.push($(this).val());
            });
            selected_users = $("#" + selectboxid).val();
            obj = {
                ids: JSON.stringify(p),
                selected_users: Array.isArray(selected_users)
                    ? JSON.stringify(selected_users)
                    : parseInt(selected_users),
                set_in_table,
                field_to_set,
            };
            let callbackSuccess = function () {
                $(".modal").hide();
            };
            objectAjaxWithBtnAndLoader(
                "btnid",
                obj,
                "/assignUser",
                callbackSuccess
            );
        }
    });
}
function deleteByAjax(rowid, btnid, url) {
    obj = { id };
    let callbackSuccess = function () {
        $("#row-" + i).hide();
    };
    objectAjaxWithBtnAndLoader(btnid, obj, url, callbackSuccess);
}
/*===============Checks password strenght with  live options===========================  */
//password-strength.js
function passwordStrengthChecker() {
    $("#password").keyup(function (event) {
        var password = $("#password").val();
        checkPasswordStrength(password);
    });
}

/**=========================================Multiple Tagged input box  search wit own custom add also =====================================*/
//Fast select https://dbrekalo.github.io/fastselect/#section-Examples
/**<input
  type="text"
    
      multiple
      class="tagsInput"
      value="Algeria,Angola"
      data-initial-value='[{"text": "Algeria", "value" : "Algeria"}, {"text": "Angola", "value" : "Angola"}]'
      data-user-option-allowed="true"
      data-url="demo/data.json"
      data-load-once="true"
  name="language"//>*/
//$('.multipleInputDynamic').fastselect();
/**==============================================Select with Live Search Ajax============================================================= */
//FastSelect
/**<input type="text" value="Algeria" data-initial-value='{"text": "Algeria", "value" : "Algeria"}' 
  class="singleInputDynamicWithInitialValue" data-url="demo/data.json" data-load-once="true" name="language" />*/

/**================================Fetch remote html content into current container */
function fetchHtmlContent(obj, container_id, url) {
    let callbackSuccess = function (res) {
        $("#" + container_id).html(res["message"]);
    };
    objectAjaxNoLoaderNoAlert(obj, url, callbackSuccess);
}
/**===============================================Check ALl Checkbox============================== */
function checkAll(is_checked) {
    $("input:checkbox").not(this).prop("checked", is_checked);
}
/**=============================SHow More Or Less Button**/
/**====Styles to add ==
  <style>
     
  .more{
      font-size:14px!important;overflow-wrap:break-word;max-width:200px;white-space:initial;
  }
  .morecontent span {
    display: none;
  }
  .morelink {
    display: block;color:orange!important;font-weight:bold;
  }
  </style>
  
  
   */
var showChar = 250; // How many characters are shown by default
var ellipsestext = "...";
var moretext = "Show more >";
var lesstext = "Show less";

$(".more").each(function () {
    var content = $(this).html();
    console.log(content);
    if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        console.log(c);
        console.log(h);
        var html =
            c +
            '<span class="moreellipses">' +
            ellipsestext +
            '&nbsp;</span><span class="morecontent"><span>' +
            h +
            '</span>&nbsp;&nbsp;<a href="" class="morelink">' +
            moretext +
            "</a></span>";
        console.log(html);
        $(this).html(html);
    }
});

$(".morelink").click(function () {
    if ($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
    } else {
        $(this).addClass("less");
        $(this).html(lesstext);
    }
    $(this).parent().prev().toggle();
    $(this).prev().toggle();
    return false;
});
/****================================================== */
/*******************View rcord in modal form not sidepopup  *******/
function viewRecord(id, url, module) {
    loading = true;
    disableBtn();
    $(`#${module}_modal .modal-body`).html(
        '<div class="spinner-border text-muted"></div>'
    );
    $(`#${module}_modal .modal-body`).css('textAlign', 'center');
    let obj = {
        id: id,
    };
    let callbackSuccess = function (res) {
        var myModal = new bootstrap.Modal(
            document.getElementById(`${module}_modal`),
            {}
        );
        myModal.show();
        enableBtn();
        setTimeout(function () {
            $(`#${module}_modal .modal-body`).html(res["message"]);
            $(`#${module}_modal .modal-body`).css("textAlign", "left");
        }, 1000);
    };
    objectAjaxNoLoaderNoAlert(obj, url, callbackSuccess, undefined, "GET");
}
/**********Delete file from separate file table **************** */
function deleteFileFromTable(id, table, folder, url) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let obj = {
                id,
                table,
                folder,
            };
            let callback = function (res) {
                // location.reload();
                $("#img_div-" + id).hide();
            };

            objectAjaxWithBtnAndLoader((btnid = undefined), obj, url, callback);
        }
    });
}
function deleteFileSelf(file_name, modelName, folder_name, field_name, row_id) {
    let url = "/delete_file_self";
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let obj = {
                file_name,
                modelName,
                folder_name,
                field_name,
                row_id,
            };
            let callback = function (res) {
                // location.reload();
                $("#img_div").hide();
            };

            objectAjaxWithBtnAndLoader((btnid = undefined), obj, url, callback);
        }
    });
}
/****Delete data from JSON colummn  */
function deleteJsonColumnData(
    row_id_val,
    inside_json_column_id,
    table,
    json_id_val,
    json_column_name,
    url
) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            let callbackSuccess = function (res) {
                $(".detail #row-" + json_id_val).hide();
            };
            let callbackError = function (res) {};

            objectAjaxWithBtnAndLoader(
                "remark_btn-",
                {
                    json_column_name,
                    table,
                    by_json_key: inside_json_column_id,
                    row_id: row_id_val,
                    json_key_val: json_id_val,
                },
                url,
                callbackSuccess,
                callbackError,
                true
            );
        }
    });
}