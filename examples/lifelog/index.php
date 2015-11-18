<?php
/*
 * Copyright 2015 Mikael Johansson
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
session_start();
require_once('../../src/Google/autoload.php');
require_once ("lifelog_inc.php");

/************************************************
  ATTENTION: Fill in these values! Make sure
  the redirect URI is to this page, e.g:
  http://localhost:8080/user-example.php
 ************************************************/
require_once('lifelog_config.php');
$client_id = $CLIENT_ID;
$client_secret = $CLIENT_SECRET;
$redirect_uri = $REDIRECT_URI;


/************************************************
 * If we're logging out we just need to clear our
 * local access token in this case
 ************************************************/
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_REQUEST['revoke'])) {
  unset($_SESSION['access_token']);
  //TODO: Implement Lifelog revoke token (if supported through REST API)
}

/************************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 ************************************************/
$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope(Google_Service_Lifelog::LIFELOG_PROFILE_READ);
$client->addScope(Google_Service_Lifelog::LIFELOG_ACTIVITY_READ);
$client->addScope(Google_Service_Lifelog::LIFELOG_LOCATION_READ);

/************************************************
 * If we have a code back from the OAuth 2.0 flow,
 * we need to exchange that with the
 * Google_Client::fetchAccessTokenWithAuthCode()
 * function. We store the resultant access token
 * bundle in the session, and redirect to ourself.
 ************************************************/
if (isset($_GET['code'])) {
  //Storing auth_code for debug purposes only
  $_SESSION['auth_code'] = $_GET['code'];
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL) ."?gotauthcode");
}

/************************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 ************************************************/

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
   $client->setAccessToken($_SESSION['access_token']);
} else {
   $authUrl = $client->createAuthUrl();
}


echo pageHeader("Google APIs Client Library for PHP with Lifelog");
echo "<p><i>Tested with Google Client v1.1.5</i></p>";
if(isset($authUrl)) {

    echo missingAccessTokenLink($authUrl);

} else {

    echo logoutLink();

    echo '<div style="border: 1px solid grey; padding: 10px; margin: 10px; word-wrap: break-word"><b>DEBUG</b><br />';
    echo "<p>code: " .$_SESSION['auth_code'] ."</p>";
    echo "<p>getAccessToken: " .$client->getAccessToken() ."</p>";
    echo "<p>getRefreshToken: " .$client->getRefreshToken() ."</p>";
    echo '</div>';

    if ($client->isAccessTokenExpired()) {
        echo "<p><b>Access token has expired.</b></p>";
        try {
           //echo "client id." .$client_id .", ". $client->getClassConfig($client, 'client_id');
           //Get a new Access token with help of the Refresh Token
           $client->refreshToken($client->getRefreshToken());
           //Update
           echo "gac: " .$client->getAccessToken();
           echo "upd getAccessCode: " .$client->getAccessToken();
           echo "ac: " .$_SESSION['access_token'];
           echo "<p><b>Access token refreshed</b></p>";
        } catch(Exception $e) {
           echo "<br />Message: " .$e->getMessage();
           echo "<br /><br />Trace: " .$e->getTraceAsString();

        }
    }

    echo "<h3>Querying Lifelog...</h3>";
    $authToken = json_decode($client->getAccessToken());

    echo "<pre>";

   

    /************************************************
     * When we create the service here, we pass the
     * client to it. The client then queries the service
     * for the required scopes, and uses that when
     * generating the authentication URL later.
     ************************************************/
    $service = new Google_Service_Lifelog($client);
    $dataSources = $service->users_dataSources;
    
    //Not yet implemented (need adaptation to Lifelogs dataset names and structure
    //$dataSets = $service->users_dataSources_datasets;

    //Google Fitness: Fetch all data sources automatically (if no second parameter is given)
    //Sony Lifelog: Fetch only the user profile data

    //Google Fitness: startTime/endTime, Sony LifeLog: start_time/end_time
    
    
    $listDataSources = NULL;
    try {
        echo "<br />Listing user data sources...<br />";
        //If this was Google Fitness we would get ALL available data 
        //(locations, activities,..), but with Lifelog we will only get
        //info about the current user
        $listDataSources = $dataSources->listUsersDataSources("me");
        
        if(count($listDataSources) > 0) {
           printf("My e-mail: %s<br/>", $listDataSources[0]['username']);
        } else {
           printf("Could not get any info about me<br/>"); 
        }
    } catch (Exception $e) {
        echo "Exception Message: " .$e->getMessage();
        echo "Exception when listing data sources: " . $e->getTraceAsString() ."<br />";
    }
    
    
    
    if ($listDataSources == NULL) {
		//Fetching data sources failed above
		$listDataSources = $dataSources->listUsersDataSources("me");
	}
    echo "<br />Getting locations from data sources<br />";
    //Setting filter paramters
    $params = array(
                  "start_time" => "2015-11-10T09:00:00.000Z",
                  "end_time" => "2015-11-16T09:00:00.000Z"
                  );
    print_r($params);
    $dataArray = $dataSources->get("me", "locations", $params);
    //Print array (or loop through it)
    print_r($dataArray);
    echo "</pre>";
}

if (strlen($client_id) < 32) {
  echo missingClientSecretsWarning();
  exit;
}
?>
