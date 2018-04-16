var user_name_exist=false;
function validate_trainee_login_name()
{
    document.getElementById('login_name').setAttribute("style", "background-color:white;")
    var login_name = document.getElementById('login_name').value;
    var params = {'verify_login': login_name};
    if (login_name.length < 1)
    {
        return;
    }
    $.post("register-user.php", params, process_trainee_login_verification_response);

}//end function

function process_trainee_login_verification_response(response, status)
{
    
    if (parseInt(response) > 0)
    {
        //alert("Login name alread exist. \nPlease enter different login name");
        $("#warning_login_name").text("Login name alread exist. \nPlease enter different login name");
        //document.getElementById('login_name').setAttribute("style", "background-color:red;");
        user_name_exist=true;
    }else
    {
        $("#warning_login_name").text("");
        user_name_exist=false;
        document.getElementById('login_name').setAttribute("style", "background-color:#d0e9c6;")
    }

}//end function

function validate_trainee_submit_form_data()
{
    var name = document.getElementById('name').value;
    var login_name = document.getElementById('login_name').value;
    var email = document.getElementById('email').value;
    var mobile = document.getElementById('mobile').value;
    var password = document.getElementById('password').value;

    if (name.length < 3)
    {
        alert("Name is required");
        document.getElementById('name').setAttribute("style", "background-color:yellow;")
        return;
    } else
    {
        document.getElementById('name').setAttribute("style", "background-color:white;")
    }

    if (login_name.length < 3)
    {
        alert("Login Name is required");
        document.getElementById('login_name').setAttribute("style", "background-color:yellow;")
        return;
    } else
    {
        document.getElementById('login_name').setAttribute("style", "background-color:white;")
    }

    if (password.length < 3)
    {
        alert("Password is required");
        document.getElementById('password').setAttribute("style", "background-color:yellow;")
        return;
    } else
    {
        document.getElementById('password').setAttribute("style", "background-color:white;")
    }
    if(!user_name_exist)
    {
        register_trainee(name,login_name,password,mobile,email);
    }else
    {
        alert("Login name alread exist. \nPlease enter different login name");
        document.getElementById('login_name').setAttribute("style", "background-color:red;");
    }
    
    
}//end function

function register_trainee(name,login_name,password,mobile,email)
{
    
    var params_and_data = {
          'name': name
        , 'login_name': login_name
        , 'password': password
        , 'mobile': mobile
        , 'register_user': "0"
        ,'email':email
    };

    $.post("register-user.php", params_and_data, process_trainee_response);
    
}//end function

function process_trainee_response(response, status)
{
    if (parseInt(response) > 0)
    {
        alert("Login name alread exist. \nPlease enter different login name")
        var e = document.getElementById('login_name')
        e.setAttribute("style", "background-color:red;");
    }else if(parseInt(response) === 0)
    {
        alert("You have registered successfully.\nPlease go to Login to login in the system.");
    }
}//end function


$(document).ready(function()
{
    $("#warning_login_name").text("");
    $("#login_name").blur(function () {
                    validate_trainee_login_name();
                });
                
    $("#register").click(function () {
                    validate_trainee_submit_form_data();
                });           
   
}
        );