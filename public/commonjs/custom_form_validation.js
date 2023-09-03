var host = "https://ecommerce.test/admin";
function getModuleWiseRules(module) {
    if (module == "Product") {
        return {
            "image[]": { required: true },
            name: { required: true },
            price: { required: true, number: true },
            stock: { required: true, number: true },
            category_id: { required: true, number: true },
        };
    } 
    if (module == "GeneratedProductStock") {
        return {
            product_id: { required: true },
            quantity: { required: true, number: true },
        };
    } else if (module == "Registration") {
        return {
            name: {
                required: true,
                minlength: 2,
            },
            email: {
                required: true,
                minlength: 2,
                email: true,
                // remote:{
                //     url: window.location.origin+'/fieldExist',
                //      type: "post",
                //      data: {
                //        value: function(){
                //            return $("#email").val();
                //        },
                //        model:'User',
                //        field:'email'
                //      }

                // }
            },
            password: {
                required: true,
                minlength: 8,
                // pwcheck:true,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password",
                minlength: 8,
                // pwcheck:true
            },
        };
    } else if (module == "User") {
        return {
            name: {
                required: true,
                minlength: 2,
            },
            email: {
                required: true,
                minlength: 2,
                email: true,
                // remote:{
                //     url: window.location.origin+'/fieldExist',
                //      type: "post",
                //      data: {
                //        value: function(){
                //            return $("#email").val();
                //        },
                //        model:'User',
                //        field:'email'
                //      }

                // }
            },
            password: {
                required: true,
                minlength: 8,
                // pwcheck:true,
            },
            phone: {
                required: true,
                number: true,
                // pwcheck:true,
            },
        };
    } else if (module == "Login") {
        return {
            name: { required: true },
        };
    } else if (module == "Category") {
        return {
            name: { required: true },
        };
    } else if (module == "ResetPassword") {
        return {
            email: {
                required: true,
                minlength: 2,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
                pwcheck: true,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password",
                minlength: 8,
                pwcheck: true,
            },
        };
    } else if (module == "Permission") {
        return {
            name: { required: true },
        };
    } else if (module == "Role") {
        return {
            name: { required: true },
            permissions: { required: true },
        };
    } else if (module == "Customer") {
        return {
            name: {
                required: true,
                minlength: 2,
            },
            email: {
                required: true,
                minlength: 2,
                email: true,
            },
            mobile_no: {
                required: true,
                number: true,
            },
            address: {
                required: true,

                maxlength: 300,
            },
            state_id: {
                required: true,

                number: true,
            },
            city_id: {
                required: true,

                number: true,
            },
        };
    } else if (module == "Setting") {
        return {
            company_name: {
                required: true,
                minlength: 2,
            },
            email: {
                required: true,
                minlength: 2,
                email: true,
            },
            mobile_no: {
                required: true,
                number: true,
            },
            address: {
                required: true,

                maxlength: 300,
            },
            gst_number: {
                required: true,
            },
            pan_number: {
                required: true,
            },
        };
    } else if (module == "CreateOrder") {
        return {
           
            product_id: {
                required: true,
                number: true,
               
            },
            quantity: {
                required: true,
                number: true,
            }
            
        };
    }
}
function getModuleWiseValidationMessages(module) {
    if (module == "Product") {
        return {
            "image[]": { required: true },
            name: { required: true },
            price: { required: true, number: true },
            stock: { required: true, number: true },
            category_id: { required: true, number: true },
        };
    } else if (module == "Registration") {
        return {
            // email:{
            //     remote:'Email already exist'
            // },
            password: {
                pwcheck: "Enter strong password",
            },
            password_confirmation: {
                pwcheck: "Enter strong password",
            },
        };
    } else if (module == "ResetPassword") {
        return {
            password: {
                pwcheck: "Enter strong password",
            },
            password_confirmation: {
                pwcheck: "Enter strong password",
            },
        };
    }
}
/**===============================Registration form submit======================== */

if ($("#registration").length > 0) {
    let rules = getModuleWiseRules("Registration");
    let messages = getModuleWiseValidationMessages("Registration");
    let callbackSuccess = function (res) {
        window.location.replace(res["url"]);
    };
    formValidateFunctionTemplate(
        rules,
        messages,
        "registration_btn",
        "registration",
        "/register",
        callbackSuccess
    );
}
/****===================================Reset Password Form validation========================== */
if ($("#password").length > 0) {
    let rules = getModuleWiseRules("ResetPassword");
    let messages = getModuleWiseValidationMessages("ResetPassword");
    formValidateFunctionTemplate(
        rules,
        messages,
        "btn",
        "pwd",
        host + "/reset_password"
    );
}
/***==============================================Login Form validation(Prefered non ajax better) ==========================================*/
if ($("#login_form").length > 0) {
    let rules = getModuleWiseRules("Login");
    let callback = function (res) {
        window.location.replace(res["redirect_url"]);
    };
    let error = function (res) {
        $(".login_error_msg").html(res);
        $("#login_btn").html("Sign in");
    };

    //formAjaxWithBtnAndLoader('login_btn','login_form','/login',callback)
    formValidateFunctionTemplateLogin(
        rules,
        {},
        "login_btn",
        "login_form",
        "/login",
        callback,
        error
    );
}
/***=========================================================Product Form validation ==========================================*/
if ($("#product_form").length > 0) {
    let rules = getModuleWiseRules("Product");
    url = $("#product_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "product_btn",
        "product_form",
        url,
        callbackSuccess
    );
}

/***=========================================================Category Form validation ==========================================*/

if ($("#category_form").length > 0) {
    let rules = getModuleWiseRules("Category");
    url = $("#category_form").attr("action");
    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "category_btn",
        "category_form",
        url,
        callbackSuccess
    );
}
/***=========================================================User Form validation ==========================================*/
if ($("#user_form").length > 0) {
    let rules = getModuleWiseRules("User");
    url = $("#user_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "user_btn",
        "user_form",
        url,
        callbackSuccess
    );
}

/*************=========================================*** */

/**==================================================Crud Form ==================================== */
/***=========================================================Product Form validation ==========================================*/
if ($("#customer_form").length > 0) {
    let rules = getModuleWiseRules("Customer");
    url = $("#customer_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "customer_btn",
        "customer_form",
        url,
        callbackSuccess
    );
}
if ($("#supplier_form").length > 0) {
    let rules = getModuleWiseRules("Customer");
    url = $("#supplier_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "supplier_btn",
        "supplier_form",
        url,
        callbackSuccess
    );
}
if ($("#setting_form").length > 0) {
    let rules = getModuleWiseRules("Setting");
    url = $("#setting_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "setting_btn",
        "setting_form",
        url,
        callbackSuccess
    );
}
if ($("#generatedproductstock_form").length > 0) {
    let rules = getModuleWiseRules("GeneratedProductStock");
    url = $("#generatedproductstock_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "generatedproductstock_btn",
        "generatedproductstock_form",
        url,
        callbackSuccess
    );
}
if ($("#createorder_form").length > 0) {
    let rules = getModuleWiseRules("CreateOrder");
    url = $("#createorder_form").attr("action");

    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 3000);
        }
    };
    formValidateFunctionTemplateImage(
        rules,
        {},
        "createorder_btn",
        "createorder_form",
        url,
        callbackSuccess
    );
}
