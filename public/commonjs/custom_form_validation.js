function getModuleWiseRules(module) {
    if (module == "Product") {
        return {
            name: { required: true },
            price: { required: true, number: true },
        };
    } else if (module == "GeneratedProductStock") {
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
                required: $("#user_form").attr("data-edit") ? false : true,
                minlength: 8,
                 pwcheck:true,
            },
            phone: {
                required: true,
                number: true,
                phone: true,
                // pwcheck:true,
            },
        };
    } else if (module == "Login") {
        return {
            email: { required: true, email: true },
            password: { required: true, minlength: 8 },
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
                phone: true,
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
            },
        };
    } else if (module == "Supplier") {
        return {
            name: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            mobile_no: {
                required: true,
                number: true,
                phone: true,
            },
        };
    } else if (module == "Remark") {
        return {
            lead_id: {
                required: true,
            },
            conversation: {
                required: true,
            },
        };
    } else if (module == "ReceivePayment") {
        return {
            title: {
                required: true,
            },
            paid_amount: {
                required: true,
                number: true,
            },
            due_amount: {
                required: true,
                number: true,
            },
            paid_date: {
                required: true,
            },
        };
    } else {
        return {};
    }
}
function getModuleWiseValidationMessages(module) {
    if (module == "Registration") {
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
    } else if (module == "Remark") {
        return {
            lead_id: {
                required: true,
            },
            conversation: {
                required: "Please enter conversation message",
            },
        };
    } else if (module == "ResetPassword") {
        return {
            password: {
                pwcheck: "Enter strong password",
            },
            password_confirmation: {
                pwcheck: "Enter strong password",
            }
            
        };
    } 
     else if (module == "User") {
        return {
            password: {
                pwcheck: "Enter strong password",
            },
            phone: {
                phone: "Enter valid phone no",
            },
        };
    } else return {};
}
function getModuleWiseCallbacks(module) {
    let callbackSuccess = function (res) {
        if (res["redirect_url"]) {
            setTimeout(function () {
                window.location.href = res["redirect_url"];
            }, 2500);
        }
    };
    let callbackError = function (error = "") {
        $("#login_btn").html("Sign-In");
    };
    if (module == "Login") {
        return { callbackSuccess, callbackError };
    } else return { callbackSuccess, callbackError };
}
