var user_name_exist=false;
function validate_login_name()
{
    document.getElementById('login_name_org').setAttribute("style", "background-color:white;")
    var login_name = document.getElementById('login_name_org').value;
    var params = {'verify_login': login_name};
    if (login_name.length < 1)
    {
        return;
    }
    $.post("ajax-org.php", params, process_login_verification_response);

}//end function

function process_login_verification_response(response, status)
{
   
    if (parseInt(response) > 0)
    {
        //alert("Login name alread exist. \nPlease enter different login name");
        //document.getElementById('login_name_org').setAttribute("style", "background-color:red;");
         $("#warning_login_name").text("Login name alread exist. \nPlease enter different login name");
        user_name_exist=true;
    }else
    {
         $("#warning_login_name").text("");
        user_name_exist=false;
        document.getElementById('login_name_org').setAttribute("style", "background-color:#d0e9c6;");
    }

}//end function


function validate_submit_form_data()
{
    var title = document.getElementById('org_title').value;
    var login_name = document.getElementById('login_name_org').value;
    var email = document.getElementById('email').value;
    var phone = document.getElementById('phone').value;
    var password = document.getElementById('password').value;
    var address = document.getElementById('address').value;

    if (title.length < 2)
    {
        alert("Title is required");
        document.getElementById('org_title').setAttribute("style", "background-color:yellow;");
        return;
    } else
    {
        document.getElementById('org_title').setAttribute("style", "background-color:white;");
    }    

    if (login_name.length < 3)
    {
        alert("Login Name is required");
        document.getElementById('login_name_org').setAttribute("style", "background-color:yellow;");
        return;
    } else
    {
        document.getElementById('login_name_org').setAttribute("style", "background-color:white;");
    }
if (email.length < 3)
    {
        alert("Email is required");
        document.getElementById('email').setAttribute("style", "background-color:yellow;");
        return;
    } else
    {
        document.getElementById('email').setAttribute("style", "background-color:white;");
    }

    if (password.length < 3)
    {
        alert("Password is required");
        document.getElementById('password').setAttribute("style", "background-color:yellow;");
        return;
    } else
    {
        document.getElementById('password').setAttribute("style", "background-color:white;");
    }
    
    if (address.length < 3)
    {
        alert("Address is required");
        document.getElementById('address').setAttribute("style", "background-color:yellow;");
        return;
    } else
    {
        document.getElementById('address').setAttribute("style", "background-color:white;");
    }
    
    if (phone.length < 3)
    {
        alert("Phone is required");
        document.getElementById('phone').setAttribute("style", "background-color:yellow;");
        return;
    } else
    {
        document.getElementById('phone').setAttribute("style", "background-color:white;");
    }
    
    
    if(!user_name_exist)
    {
        return true;
    }else
    {
        alert("Login name alread exist. \nPlease enter different login name");
        document.getElementById('login_name').setAttribute("style", "background-color:red;");
    }
    
    return false;
}//end function


$(document).ready(function()
{
    $("#login_name_org").blur(function () {
                    validate_login_name();
                });             
            
}
        );