
# Google APIs Client Library for PHP with Lifelog #

## Description ##
The Google API Client Library with Lifelog enables you to work with Sony Lifelog and also Google APIs such as Google+, Drive, or YouTube on your server. 

NOTE! This is NOT a project by Google or Sony.

## Development version ##
This library is in development version. This can be seen as a proof of concept, but will hopefully be more mature in the future. It is based on Google Client v.1.1.5 (beta), https://github.com/google/google-api-php-client.

## Requirements ##
* [PHP 5.2.1 or higher](http://www.php.net/)
* [PHP JSON extension](http://php.net/manual/en/book.json.php)

*Note*: some features (service accounts and id token verification) require PHP 5.3.0 and above due to cryptographic algorithm requirements. 

## Developer Documentation ##
TBD

## Installation ##
TBD

## Basic Example ##
See the examples/ directory for examples of the key client features.
```PHP
<?php

  require_once 'google-api-php-client-with-lifelog/src/Google/autoload.php'; // or wherever autoload.php is located
  
  $client = new Google_Client();
  $client->setClientId($client_id);
  $client->setClientSecret($client_secret);
  $client->setRedirectUri($redirect_uri);
  $client->addScope(Google_Service_Lifelog::LIFELOG_PROFILE_READ);
  $client->addScope(Google_Service_Lifelog::LIFELOG_ACTIVITY_READ);
  $client->addScope(Google_Service_Lifelog::LIFELOG_LOCATION_READ);
  
  $service = new Google_Service_Lifelog($client);
  $dataSources = $service->users_dataSources;
  $listDataSources = $dataSources->listUsersDataSources("me");
  printf("My e-mail: %s<br/>", $listDataSources[0]['username']);
  
```

