<?php


// class to access moodle installed on AWS and create Users login for Trainee when they are registeed in Planit APP.
class Moodle {

    // A token is needed to acess moodle service to create user aor do any thing.
    var $token = '2a71b50a8df9a0121a53199fe2f8e579';  // Token to access Moodle server. Must be configured in Moodle. See readme.
    // following is URL of VM where moodle is installed.
    var $server = 'http://18.218.207.117'; // Moodle URL, for example http://localhost:8080.
    // followng is directory where moodle is installed on AWS VM
    var $dir = '/moodle';     // Directory on the server. For example, /moodle. If your moodle runs as root, this is empty.
    var $error = '';    // Last error of the class. We'll write the last error here when something wrong happens. 

    // The createUser function tries to create a new Moodle user.
    
    //$fields is an array containg users info being created in moodle
    function createUser($fields) {
        // Clear last error.
        $this->error = null;
        
        // $userFields is another array to store user info in required format of moodle which may not followed in $fileds.
        // Construct user fields array.
        $userFields = array();
        if (isset($fields['username']))
            $userFields['username'] = $fields['username'];
        if (isset($fields['password']))
            $userFields['password'] = $fields['password'];
        if (isset($fields['firstname']))
            $userFields['firstname'] = $fields['firstname'];
        if (isset($fields['lastname']))
            $userFields['lastname'] = $fields['lastname'];
        if (isset($fields['email']))
            $userFields['email'] = $fields['email'];
        if (isset($fields['city']))
            $userFields['city'] = $fields['city'];
        if (isset($fields['country']))
            $userFields['country'] = $fields['country'];
        // if (isset($fields['auth'])) $userFields['auth'] = $fields['auth'];
        if (isset($fields['preferences']))
            $userFields['preferences'] = $fields['preferences'];
        // customfields field option is used to send custom data and I am sending Name of organization in customfields.
// All above fields e.g. username, password, firstname, lastname are standared names in moodle to create a user
        if (isset($fields['customfields']))
            $userFields['customfields'] = $fields['customfields'];
        
        // Create XML for the request. XML must be set properly for this to work. 
        //creating a request object to send xmlrpc call.
        //core_user_create_users  is functionality to perfom in moodle and must be enabled from admin side in moodle. 
        $request = xmlrpc_encode_request('core_user_create_users', array(array($userFields)), array('encoding' => 'UTF-8'));
        //var_dump($request);  // In case you want to see XML.
        //creating a $context object to send xmlrpc call. 
        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));
        // $path  compolete URL with token where service on Moodle is running
        $path = $this->server . $this->dir . "/webservice/xmlrpc/server.php?wstoken=" . $this->token;

       
// Send XML to server file_get_contentsand get a reply from it.
        $file = file_get_contents($path, false, $context); // $file is the reply from server. 
        // Decode the reply.
        $response = xmlrpc_decode($file);
        //$response will be an array holding created user info in case of successful request on moodle
        // Note: lack of permissions on Moodle will get us an error.
        // moodle/user:create capability is required for web service account to call core_user_create_users.
        // Handle errors.
        if (!is_array($response) || !is_array($response[0]) || !array_key_exists('id', $response[0])) {
            // We have an error.
            if ($response[faultCode])
                $this->error = 'Moodle error: ' . $response[faultString] . ". Fault code: " . $response[faultCode] . ".";
            else
                $this->error = 'Moodle returned no info. Check if Moodle is set up properly (see readme).';
            $this->error .= " Actual reply from server: " . $file;
            
            //if error occurs function will return from here with error.
            return false;
        }

        // in case of success following code will be executed. 
        // This is our normal exit. Returning a 2-member array with new user id and username.
        $user = $response[0];
        return $user;
    }// end of function createUser()

    
    
    // All following ccode is for sake of completion and copied from tutorial for future usage if needed. Not using right now  
    
    // The getUser function obtains information for a Moodle user identified by its id.

    function getUser($user_id) {
        // Clear last error.
        $this->error = null;

        // Create XML for the request. XML must be set properly for this to work.
        //$request = xmlrpc_encode_request('core_user_get_users_by_id', array(array((string) $user_id)), array('encoding' => 'UTF-8'));


        $user = new MyObject();
        $user->key = 'userid';
        $user->value = $user_id;

/// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION

        $params = array(array($user));

        $request = xmlrpc_encode_request('core_user_get_users_by_field', array("id", array((string) $user_id)), array('encoding' => 'UTF-8'));




        // var_dump($request);  // In case you want to see XML.

        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));

        $path = $this->server . $this->dir . "/webservice/xmlrpc/server.php?wstoken=" . $this->token;
        // Send XML to server and get a reply from it.
        $file = file_get_contents($path, false, $context); // $file is the reply from server.
        // Decode the reply.
        $response = xmlrpc_decode($file);

        var_dump($response);
        die();

        // Note: lack of permissions on Moodle will get us an XML-formatted response with NULL values.
        // In other words, one must be absolutely sure to give all the required capabilities to web services account
        // in order to execute this function successfully. Moodle says that we need the following:
        // moodle/user:viewdetails, moodle/user:viewhiddendetails, moodle/course:useremail, moodle/user:update
        // for core_user_get_users_by_id call.
        // Handle errors.
        if (!is_array($response) || !is_array($response[0]) || !array_key_exists('id', $response[0])) {
            // We have an error.
            if ($response[faultCode])
                $this->error = 'Moodle error: ' . $response[faultString] . ". Fault code: " . $response[faultCode] . ".";
            else
                $this->error = 'Moodle returned no info. Check if user id exists and whether the web service
          account has capabilities required to execute core_user_get_users_by_id call.';
            $this->error .= " Actual reply from server: " . $file;
            return false;
        }

        // This is our normal exit (returning an array of user properties).
        $user = $response[0];
        return $user;
    }

    
    // The createUser function tries to update an existing Moodle user.
    function updateUser($fields) {
        // Clear last error.
        $this->error = null;

        // Check if user exists.
        $user = $this->getUser($fields['id']);
        if (!$user)
            return false;

        // Construct user fields array.
        $userFields = array();
        if (isset($fields['id']))
            $userFields['id'] = $fields['id'];
        if (isset($fields['username']))
            $userFields['username'] = $fields['username'];
        if (isset($fields['password']))
            $userFields['password'] = $fields['password'];
        if (isset($fields['firstname']))
            $userFields['firstname'] = $fields['firstname'];
        if (isset($fields['lastname']))
            $userFields['lastname'] = $fields['lastname'];
        if (isset($fields['email']))
            $userFields['email'] = $fields['email'];
        if (isset($fields['city']))
            $userFields['city'] = $fields['city'];
        if (isset($fields['country']))
            $userFields['country'] = $fields['country'];
        if (isset($fields['auth']))
            $userFields['auth'] = $fields['auth'];
        if (isset($fields['preferences']))
            $userFields['preferences'] = $fields['preferences'];

        // Create XML for the request. XML must be set properly for this to work.
        $request = xmlrpc_encode_request('core_user_update_users', array(array($userFields)), array('encoding' => 'UTF-8'));
        // var_dump($request);  // In case you want to see XML.

        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));

        $path = $this->server . $this->dir . "/webservice/xmlrpc/server.php?wstoken=" . $this->token;
        // Send XML to server and get a reply from it.
        $file = file_get_contents($path, false, $context); // $file is the reply from server.
        // Decode the reply.
        $response = xmlrpc_decode($file);

        // Note: lack of permissions on Moodle will get us an error.
        // moodle/user:update capability is required for web service account to call core_user_update_users.

        if ($response && xmlrpc_is_fault($response)) {
            $this->error = 'Moodle error: ' . $response[faultString] . ". Fault code: " . $response[faultCode] . ".";
            $this->error .= " Actual reply from server: " . $file;
            return false;
        }

        // This is our normal exit after a successful update.
        return true;
    }

    // The deleteUser function tries to delete an existing Moodle user.
    function deleteUser($user_id) {
        // Clear last error.
        $this->error = null;

        // Check if user exists.
        $user = $this->getUser($user_id);
        if (!$user)
            return false;

        // Create XML for the request. XML must be set properly for this to work.
        $request = xmlrpc_encode_request('core_user_delete_users', array(array((string) $user_id)), array('encoding' => 'UTF-8'));
        // var_dump($request);  // In case you want to see XML.

        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));

        $path = $this->server . $this->dir . "/webservice/xmlrpc/server.php?wstoken=" . $this->token;
        // Send XML to server and get a reply from it.
        $file = file_get_contents($path, false, $context); // $file is the reply from server.
        // Decode the reply.
        $response = xmlrpc_decode($file);

        if ($response && xmlrpc_is_fault($response)) {
            $this->error = "Moodle error: " . $response[faultString] . " Fault code: " . $response[faultCode];
            return false;
        }

        // This is our normal exit after a successful delete.
        return true;
    }

    // The getCourse function obtains information for a Moodle course identified by its id.
    function getCourse($id) {
        // Clear last error.
        $this->error = null;

        // Create XML for the request. XML must be set properly for this to work.
        $courseids = array($id);
        // $params = array('options'=>array('ids'=>$courseids)); // This does not work, gets us an exception inside Moodle.
        $params = array(array('ids' => $courseids)); // This works.
        $request = xmlrpc_encode_request('core_course_get_courses', $params, array('encoding' => 'UTF-8'));
        // var_dump($request);  // In case you want to see XML.

        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));

        $path = $this->server . $this->dir . "/webservice/xmlrpc/server.php?wstoken=" . $this->token;
        // Send XML to server and get a reply from it.
        $file = file_get_contents($path, false, $context); // $file is the reply from server.
        // Decode the reply.
        $response = xmlrpc_decode($file);

        // Note: lack of permissions on Moodle will get us an error.
        // Required capabilities for core_course_get_courses call:
        // moodle/course:view,moodle/course:update,moodle/course:viewhiddencourses
        // Make sure that your web service account role has those.
        // Handle errors.
        if (!is_array($response) || !is_array($response[0]) || !array_key_exists('id', $response[0])) {
            // We have an error.
            if ($response[faultCode])
                $this->error = 'Moodle error: ' . $response[faultString] . ". Fault code: " . $response[faultCode] . ".";
            else
                $this->error = "Moodle returned no info. Check if course id exists and whether the web service
          account has capabilities required to execute core_course_get_courses call.";
            $this->error .= " Actual reply from server: " . $file;
            return false;
        }

        // This is our normal exit (returning an array of course properties).
        $course = $response[0];
        return $course;
    }

    // The enrollUser function tries to enroll user in a course.
    function enrollUser($user_id, $course_id) {
        // Clear last error.
        $this->error = null;

        // Check whether user exists.
        $user = $this->getUser($user_id);
        if (!$user)
            return false;

        // Here, you may wish to check $user['enrolledcourses'] to see if a user is already enrolled in a course.
        // Check whether course exists.
        $course = $this->getCourse($course_id);
        if (!$course)
            return false;

        // Create XML for the request. XML must be set properly for this to work.  This format was hard to figure out.
        // I needed to debug the server code so see why method signatures did not match.
        $params = array(array(array('roleid' => '5', 'userid' => $user_id, 'courseid' => $course_id))); // roleid 5 is "student".
        $request = xmlrpc_encode_request('enrol_manual_enrol_users', $params, array('encoding' => 'UTF-8'));
        // var_dump($request);  // In case you want to see XML.

        $context = stream_context_create(array('http' => array(
                'method' => "POST",
                'header' => "Content-Type: text/xml",
                'content' => $request
        )));

        $path = $this->server . $this->dir . "/webservice/xmlrpc/server.php?wstoken=" . $this->token;
        // Send XML to server and get a reply from it.
        $file = file_get_contents($path, false, $context); // $file is the reply from server.
        // Decode the reply.
        $response = xmlrpc_decode($file);

        // enrol/manual:enrol capability is required for the web services account.
        // Also, the account must be abble to assign the "Student" role - this is configured in
        // Site administration - Users - Permissions - Define roles - Allow role assignments (make sure that the "Student" role
        // is checked for Web Services Users category (this is my custom role for web services account).

        if ($response && xmlrpc_is_fault($response)) {
            $this->error = "Moodle error: " . $response[faultString] . " Fault code: " . $response[faultCode];
            return false;
        }

        // Here, you may wish to check $user['enrolledcourses'] to see if a user gor enrolled, just to be safe.
        // $user = $this->getUser($user_id);
        // This is our normal exit after a successful enrollment.
        return true;
    }

}

//end classs

class MyObject {
    
}

//end class
