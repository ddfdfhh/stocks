var host = "https://ecommerce.test/admin";
// $("#image").filer({
//     showThumbs: true,
//     addMore: true,
//     allowDuplicates: falch,
// });
$(document).ready(function () {
    $("#inp-state_id").on("change", function () {
        let val = $(this).val();
        // fetchHtmlContent({state:val},'inp-city',host+'/getCity');
        showDependentSelectBox(
            "state_id",
            "name",
            val,
            "inp-city_id",
            "city",
            "id"
        );
    });
});
function applySelect2(elem, in_popup = true, container_id = null) {
    let options = { placeholder: "Select.." };
    if (in_popup) options["dropdownParent"] = $("#" + container_id);
    if ($(elem).length > 0) {
        if ($(elem)[0].length > 0) {
            $(elem).each(function () {
                let tag = $(this).attr("data-tag") == undefined ? false : true;

                if ($(this).attr("multiple")) {
                    options["tokenSeparators"] = [",", " "];
                    options["tags"] = tag;
                    options["multiple"] = true;
                }

                $(this).select2(options);
            });
        } else {
            let tag = $(this).attr("data-tag") == undefined ? false : true;
            if ($(this).attr("multiple")) {
                options["tokenSeparators"] = [",", " "];
                options["tags"] = tag;
                options["multiple"] = true;
            }
            $(this).select2(options);
        }
    }
}
function applySelect2ChangeEventPopulateOther(data = {}) {
    /**
     * data={
     * parent_id:
     * dependent_id:
     * dependee_key:
     * dependent_key
     * ependent_select_box_id:,
     * dependent_table:,
     * dependent_table_table_id,
     * callback:
     * }
     */
    if ($("#" + data["parent_id"]).length > 0) {
        $("#" + data["parent_id"]).on("change", function () {
            let val = $(this).val();

            if (Array.isArray(val) && val.length > 0) {
                /*to store json not with id but with name*/

                if (val[0].includes("-")) {
                    let spl = val[0].split("-");
                    if (
                        spl.length == 2 &&
                        Number.isInteger(spl[0]) &&
                        !Number.isInteger(spl[1])
                    ) {
                        val = val.map(function (v) {
                            return v.split("-")[0];
                        });
                    }
                }
                showDependentSelectBoxForMultiSelect(
                    data["dependee_key"],
                    data["dependent_key"],
                    val,
                    data["dependent_id"],
                    data["dependent_table"],
                    data["dependent_table_table_id"],
                    data["callback"]
                );
            } else showDependentSelectBox(data["dependee_key"], data["dependent_key"], val, data["dependent_id"], data["dependent_table"], data["dependent_table_table_id"], data["callback"]);
        });
    }
}
function initiateSelect2ChangeEvents(in_popup = true, container_id = null) {
    let data_state = {
        parent_id: "inp-state",
        dependent_id: "inp-city",
        dependee_key: "state" /***in child table column for parent */,
        dependent_key:
            "name" /***in child table column name for childin option name */,
        dependent_select_box_id: "inp-city",
        dependent_table: "cities",
        dependent_table_table_id: "id",
        callback: function () {
            applySelect2("#inp-city", in_popup, container_id);
        },
    };
    applySelect2ChangeEventPopulateOther(data_state);
    let data_country = {
        parent_id: "inp-country",
        dependent_id: "inp-state",
        dependee_key: "country" /***in child table column for parent */,
        dependent_key:
            "name" /***in child table column name for childin option name */,
        dependent_select_box_id: "inp-state",
        dependent_table: "states",
        dependent_table_table_id: "id",
        callback: function () {
            applySelect2("#inp-state", in_popup, container_id);
        },
    };
    applySelect2ChangeEventPopulateOther(data_country);
    let data_city = {
        parent_id: "inp-city",
        dependent_id: "inp-pincode",
        dependee_key: "city" /***in child table column for parent */,
        dependent_key:
            "name" /***in child table column name for childin option name */,
        dependent_select_box_id: "inp-pincode",
        dependent_table: "pincodes",
        dependent_table_table_id: "id",
        callback: function () {
            applySelect2("#inp-pincode", in_popup, container_id);
        },
    };
    applySelect2ChangeEventPopulateOther(data_city);
}
function formInitiate(module, rules) {
    let lowercase_name = module.toLowerCase();
    url = $("#" + lowercase_name + "_form").attr("action");

    let formSubmitcallbackSuccess = function (res) {
        if (res["redirect_url"]) {
            // bsOffcanvas.hide();
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        lowercase_name + "_btn",
        lowercase_name + "_form",
        url,
        formSubmitcallbackSuccess
    );
}
function inilizeEvents() {
    $("#filter").on("hide.bs.dropdown", function (e) {
        if (e.clickEvent) {
            e.preventDefault();
        }
    });

    //applySelect2("select", false);
    initiateSelect2ChangeEvents(false);
    // applySelect2("#inp-country",false,false)
    /****select 2 in filter and user assing modal area  */
    const myModalEl = document.getElementById("myModal");
    myModalEl.addEventListener("shown.bs.modal", (event) => {
        applySelect2("select", true, "myModal");
        // do something...
    });
    var myDropdown = document.getElementById("filter");
    myDropdown.addEventListener("shown.bs.dropdown", function () {
        applySelect2("select", true, "filter");
    });
    if ($("#tax_form").length > 0) {
        /***initliase form submit here*/
        formInitiate("tax", rules);
    }
    //if ($("form#try").length > 0) applySelect2("select",true,true,"form#try",);

    /***** */
    if ($("#image").length > 0) {
        $("#image").on("change", function () {
            multiImagePreview(this, "gallery1");
        });
    }
    if ($("#inp-image").length > 0) {
        $("#inp-image").on("change", function () {
            /***always take for single image filed name image ,here inp is aapended automatically to image id */
            singleImagePreview(this, "gallery1");
        });
    }
    if ($("#inp-password").length > 0) {
        $("#inp-password").keyup(function (event) {
            var password = $("#password").val();
            checkPasswordStrength(password);
        });
    }

    $("input[name=has_variant]").on("change", function (v) {
        $("#add_variant").toggle();
    });
}

function showToggableDivOnLoadIfPresent() {
    if ($(".toggable_div").length > 0) {
    
        $(".toggable_div").each(function () {
            let id = $(this).attr("id");
            let colname = $(this).attr("data-colname");
            
            let inputidforval = $(this).data("inputidforvalue");
            let rowid = $(this).data("rowid");
            console.log(inputidforval);
            if (inputidforval.length > 0) {
                let val = inputidforval;

                let module = $(this).data("module");

                objectAjaxNoLoaderNoAlert(
                    { val: val, row_id: rowid, colname },
                    `/admin/${module.toLowerCase()}/load_snippets`,
                    (htmlLoadcallback = function (res) {
                        $("#" + id).html(res["message"]);
                    })
                );
            }
        });
    }
}
function initFilePreviewEvent() {
    $("input[type=file]").each(function () {
        let el = this;
        if ($(el).attr("multiple")) {
            $(el).filer({
                showThumbs: true,
                addMore: true,
                allowDuplicates: false,
            });
        } else {
            $(this).filestyle({
                text: "Choose",
                placeholder: "Choose file to upload",
            });
            $(el).change(function () {
                let f = this;
                const file = f.files[0];
                console.log(file);
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        let y = event.target.result;
                        $(f)
                            .parent()
                            .append(
                                `<img src='${y}'  class='img_rounded' style='width:100px;height:100px;margin:5px' />`
                            );
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
}
function initializeFormAjaxSubmitAndValidation() {
    $("form").each(function () {
        let module = $(this).data("module");
        let url = $(this).attr("action");
        let rules = getModuleWiseRules(module);
        let lowercase_name = module.toLowerCase();
        let formSubmitcallbackSuccess = function (res) {
            if (res["redirect_url"]) {
                setTimeout(function () {
                    window.location.href = res["redirect_url"];
                }, 3000);
            }
        };
        formValidateFunctionTemplateImage(
            rules,
            {},
            lowercase_name + "_btn",
            lowercase_name + "_form",
            url,
            formSubmitcallbackSuccess
        );
    });
}
function initialiseSummernote() {
    if ($(".summernote").length > 0) {
        $(".summernote").each(function (el) {
            $(this).summernote();
        });
    }
}
$(document).ready(function () {
    initialiseSummernote();
    inilizeEvents();

    showToggableDivOnLoadIfPresent();
    initFilePreviewEvent();
    if ($("form").length > 0) initializeFormAjaxSubmitAndValidation();
});
//$('.multipleInputDynamic').fastselect();
/**==============================================Add More Row of inputs ================================ */
function addMoreRow() {
    let parent = $(event.target).closest(".repeatable");
    console.log(parent);
    let copy_content = parent.find(".copy_row")[0];
    console.log(copy_content);
    $(copy_content).clone().appendTo(parent);
}
function removeRow() {
    let parent = $(event.target).closest(".repeatable");

    if (parent.find(".copy_row").length > 1)
        parent.children(".copy_row").last().remove();
}
/*=======================Toggle Div Display based on input val================================*/
function toggleDivDisplay(field, val, module, container_id, row_id = null) {
    /*here modeule is plural_lowercae*/

    objectAjaxNoLoaderNoAlert(
        { val: val, row_id, colname: field },
        `/admin/${module.toLowerCase()}/load_snippets`,
        (htmlLoadcallback = function (res) {
            console.log(res);
            $("#" + container_id).html(res["message"]);
        })
    );
}
function deleteRecord(id, url) {
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
            let formData = { id: id, _method: "DELETE" };
            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                data: formData,
                beforeSend: function () {
                    blockUi();
                },
                success: function (res) {
                    successAlert("Record Deleted successfully");
                    $("#row-" + id).hide();
                },
                complete: function () {
                    unBlockUi();
                },
                error: function (xhr, status, errorThrown) {
                    formatErrorMessage(xhr, errorThrown);
                },
            });
        }
    });
}

/*************This function when loading form in offcanvas modal from right ************** */

function load_form(module, form_type, url, id = null, properName) {
    let lowercase_name = module.toLowerCase();
    var myOffcanvas = document.getElementById("offcanvasEnd");
    $("#offcanvasEnd .offcanvas-body").addClass("text-center");
    $(".offcanvas-title").html(form_type + "&nbsp;&nbsp;" + properName);
    $("#offcanvasEnd .offcanvas-body").html(
        "<div class='spinner-border' style='position:absolute;top: 50%;left:50%'></div>"
    );
    var bsOffcanvas = new bootstrap.Offcanvas(myOffcanvas);
    bsOffcanvas.show();
    let obj = {
        id,
        form_type,
    };
    let htmlLoadcallback = function (res) {
        $("#offcanvasEnd .offcanvas-body").removeClass("text-center");
        $("#offcanvasEnd .offcanvas-body").html(res["message"]);

        initialiseSummernote();

        applySelect2("select", (in_popup = true), "offcanvasEnd");
        initiateSelect2ChangeEvents(true, "offcanvasEnd");
        initFilePreviewEvent();
        let rules = getModuleWiseRules(module);
        showToggableDivOnLoadIfPresent();
        if ($("#" + lowercase_name + "_form").length > 0) {
            /***initliase form submit here*/
            url = $("#" + lowercase_name + "_form").attr("action");

            let formSubmitcallbackSuccess = function (res) {
                if (res["redirect_url"]) {
                    bsOffcanvas.hide();
                    setTimeout(function () {
                        window.location.href = res["redirect_url"];
                    }, 3000);
                }
            };
            formValidateFunctionTemplateImage(
                rules,
                {},
                lowercase_name + "_btn",
                lowercase_name + "_form",
                url,
                formSubmitcallbackSuccess
            );
        }
    };

    objectAjaxNoLoaderNoAlert(
        obj,
        url,
        htmlLoadcallback
    ); /**called to load form */
}
