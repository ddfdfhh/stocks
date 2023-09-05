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

function inilizeEvents() {
    if ($("#filter").length > 0) {
        $("#filter").on("hide.bs.dropdown", function (e) {
            if (e.clickEvent) {
                e.preventDefault();
            }
        });
    }

    //applySelect2("select", false);
    initiateSelect2ChangeEvents(false);
    // applySelect2("#inp-country",false,false)
    /****select 2 in filter and user assing modal area  */
    const myModalEl = document.getElementById("myModal");
    if (myModalEl) {
        myModalEl.addEventListener("shown.bs.modal", (event) => {
            applySelect2("select", true, "myModal");
            // do something...
        });
    }
    var myDropdown = document.getElementById("filter");
    if (myDropdown) {
        myDropdown.addEventListener("shown.bs.dropdown", function () {
            applySelect2("select", true, "filter");
        });
    }
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
        console.log("all fom", module);
        let url = $(this).attr("action");
        let rules = getModuleWiseRules(module);
        let has_file = false;
        let this_form = this;
        $(this_form)
            .find("input")
            .each(function (el) {
                let input = this;
                if ($(input).attr("type") === "file") {
                    has_file = true;
                    return false;
                }
            });
        let messages = getModuleWiseValidationMessages(module);
        let lowercase_name = module.toLowerCase();
        let { callbackSuccess, callbackError } = getModuleWiseCallbacks(module);
        show_server_validation_in_alert = true;
        if (module == "Login" || module == "Registration")
            show_server_validation_in_alert = false;
        formValidateFunctionTemplate(
            rules,
            messages,
            lowercase_name + "_btn",
            lowercase_name + "_form",
            url,
            callbackSuccess,
            callbackError,
            has_file,
            show_server_validation_in_alert
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
function setUnitOnMaterialSelect(material_id) {
    console.log("working");
    objectAjaxNoLoaderNoAlert(
        { material_id },
        `/getUnitByMeterialId`,
        (htmlLoadcallback = function (res) {
            console.log(res);
            $("#unit").html(res["message"]);
        })
    );
}
function generateInvoice(order_id) {
    const myModalEl = new bootstrap.Modal(
        document.getElementById("invoiceModal")
    );
    myModalEl.toggle();
    $("#invoiceModal #invoice-body").css("textAlign", "center");
    $("#invoiceModal #invoice-body").html(
        '<div class="spinner-border text-muted mx-auto mt-3"></div>'
    );
    const callbackError = function (res) {
        console.log(res);
    };
    objectAjaxNoLoaderNoAlert(
        { order_id },
        `/admin/generate_invoice`,
        (htmlLoadcallback = function (res) {
            $("#invoiceModal #invoice-body").css("textAlign", "left");
            $("#invoiceModal #invoice-body").html(res["message"]);
        }),
        callbackError,
        "POST"
    );
}
$(document).ready(function () {
    if ($("form").length > 0) initializeFormAjaxSubmitAndValidation();
    applySelect2("select", false);
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
    initialiseSummernote();
    inilizeEvents();

    showToggableDivOnLoadIfPresent();
    initFilePreviewEvent();
});
//$('.multipleInputDynamic').fastselect();
/**==============================================Add More Row of inputs ================================ */
function addMoreRow() {
    let parent = $(event.target).closest(".repeatable");

    let copy_content = parent.find(".copy_row")[0];
    let has_select=$(copy_content).find(".select2").length;
    if (has_select) {
        $(copy_content).find(".select2").remove();
    }
    $(copy_content).clone().appendTo(parent);
    $("select").each(function (i, obj) {
        if (!$(obj).data("select2")) {
            $(obj).select2();
        }
    });
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
function initializeModalFormValidation(module, bsOffcanvas) {
    let rules = getModuleWiseRules(module);
    let messages = getModuleWiseValidationMessages(module);

    let lowercase_name = module.toLowerCase();
    if ($("#" + lowercase_name + "_form").length > 0) {
        let has_file = false;

        $("#" + lowercase_name + "_form")
            .find("input")
            .each(function (el) {
                if ($(this).attr("type") === "file") {
                    has_file = true;
                    return false;
                }
            });
        url = $("#" + lowercase_name + "_form").attr("action");

        let { callbackSuccess, callbackError } = getModuleWiseCallbacks(module);

        formValidateFunctionTemplate(
            rules,
            messages,
            lowercase_name + "_btn",
            lowercase_name + "_form",
            url,
            callbackSuccess,
            callbackError,
            has_file
        );
    }
}
/*************This function when loading form in offcanvas modal from right ************** */

function load_form(module, form_type, url, id = null, properName) {
    let lowercase_name = module.toLowerCase();
    var myOffcanvas = document.getElementById("offcanvasEnd");
    $("#offcanvasEnd .offcanvas-body").addClass("text-center");
    properName = properName.replace("Create", "");

    $("#offcanvasEndLabel").html(form_type + "&nbsp;&nbsp;" + properName);
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

        showToggableDivOnLoadIfPresent();
        initializeModalFormValidation(module, bsOffcanvas);
    };

    objectAjaxNoLoaderNoAlert(
        obj,
        url,
        htmlLoadcallback
    ); /**called to load form */
}
