<?php

class Moodle {

    public $token;          //'2a71b50a8df9a0121a53199fe2f8e579';
    public $domainName;     // 'http://18.218.207.117/moodle';
    public $serverUrl;
    public $error;

    public function __construct($token, $domainName) {
        $this->token = $token;
        $this->domainName = $domainName;

        $this->serverUrl = $this->domainName . '/webservice/rest/server.php' . '?wstoken=' . $this->token;

        echo "initialize Service: $this->serverUrl </br>";
    }

    public function createUser() {
        $functionName = 'core_user_create_users';

        $user1 = new stdClass();
        $user1->username = 'testusername1';
        $user1->password = 'Uk3@0d5w';
        $user1->firstname = 'testfirstname1';
        $user1->lastname = 'testlastname1';
        $user1->email = 'testemail1@moodle.com';
        $user1->auth = 'manual';
        $user1->idnumber = '';
        $user1->lang = 'en';
        $user1->timezone = 'Australia/Sydney';
        $user1->mailformat = 0;
        $user1->description = '';
        $user1->city = '';
        $user1->country = 'AU';     //list of abrevations is in yourmoodle/lang/en/countries
        $preferencename1 = 'auth_forcepasswordchange';
        $user1->preferences = array(
            array('type' => $preferencename1, 'value' => 'true')
            );

        $users = array($user1);
        $params = array('users' => $users);

        /// REST CALL
        $restformat = "json";
        $serverurl = $this->serverUrl . '&wsfunction=' . $functionName. '&moodlewsrestformat=' . $restformat;
        //require_once (DOCUMENT_ROOT . '/tcm/api/moodle/curl.php');
        $curl = new curl();


        $resp = $curl->post($serverurl, $params);


        echo '</br>************************** Server Response    createUser()**************************</br></br>';
        echo $serverurl . '</br></br>';

        var_dump($resp);
    }
}