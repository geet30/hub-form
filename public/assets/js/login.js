jQuery(document).ready(function () {
    
    //Login Form validation
    jQuery('#UserLoginForm').validate({
        rules :{
            email: {
                required: true,
                email: true,
            },
            password:{
                required: true,
            },
            terms_condition:{
                required: true,
            },
        },
        messages :{
            email: {
                required: "Please enter Email",
                email: "Please enter valid Email",
            },
            password:{
                required: "Please enter Password",
            },
            terms_condition:{
                required: "Please accept terms and conditions",
            },
        }

    });

    //forgot password validation
    jQuery('#forgotPassword').validate({
        rules :{
            email: {
                required: true,
                email: true,
            }
        },
        messages :{
            email: {
                required: "Please enter Email",
                email: "Please enter valid Email",
            }
        }

    });

    //reset password validation
    jQuery('#resetPassword').validate({
        rules :{
            password:{
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            cpassword:{
                required: true,
                minlength: 8,
                maxlength: 20,
                equalTo: "#password"
            },
        },
        messages :{
            password:{
                required: "Please enter Password",
                minlength: "Please enter at least 8 characters.",
                maxlength: "Please enter no more than 20 characters.",
            },
            cpassword:{
                required: "Please enter Confirm Password",
                minlength: "Please enter at least 8 characters.",
                maxlength: "Please enter no more than 20 characters.",
                equalTo: "Confirm Password in not same as Password"
            },
        }

    });

});