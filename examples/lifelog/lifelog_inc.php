<?php
/* Ad hoc functions to make the examples marginally prettier.*/
function isWebRequest()
{
  return isset($_SERVER['HTTP_USER_AGENT']);
}

function pageHeader($title)
{
  $ret = "";
  if (isWebRequest()) {
    $ret .= sprintf('<!doctype html>
    <html>
    <head>
      <title>%s</title>
      <link href="styles/style.css" rel="stylesheet" type="text/css" />
      <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Roboto">
      <style>
      body {
        font-family: \'Roboto\', serif;
      }
      </style>
    </head>
    <body>', $title);
    if ($_SERVER['PHP_SELF'] != "/index.php") {
      $ret .= "<p><a href='index.php'>Start page</a>&nbsp;&nbsp;&nbsp;</p>";
    }
    $ret .= '<header><h1><div style="color:#000088">Development version</div>' . colorizedLifelog($title) . '</h1></header>';
  }
  return $ret;
}
function colorizedLifelog($text) {
	if (strpos($text, "Lifelog") >= 0) {
		$colorized = sprintf('<span style="color:#039BE5">Li</span><span style="color:#00E676">fe</span><span style="color:#FFA000">lo</span><span style="color:#AA00FF">g</span>');
		return str_replace("Lifelog",$colorized, $text);
    }
	return $text;
}

function pageFooter($file = null)
{
  $ret = "";
  if (isWebRequest()) {
    // Echo the code if in an example.
    if ($file) {
      $ret .= "<h3>Code:</h3>";
      $ret .= "<pre class='code'>";
      $ret .= htmlspecialchars(file_get_contents($file));
      $ret .= "</pre>";
    }
    $ret .= "</html>";
  }
  return $ret;
}

function missingApiKeyWarning()
{
  $ret = "";
  if (isWebRequest()) {
    $ret = "
      <h3 class='warn'>
        Warning: You need to set a Simple API Access key from the
        <a href='http://developers.google.com/console'>Google API console</a>
      </h3>";
  } else {
    $ret = "Warning: You need to set a Simple API Access key from the Google API console:";
    $ret .= "\nhttp://developers.google.com/console\n";
  }
  return $ret;
}

function missingClientSecretsWarning()
{
  $ret = "";
  if (isWebRequest()) {
    $ret = "
      <h3 class='warn'>
        Warning: You need to set Client ID, Client Secret and Redirect URI from the
        <a href='http://developers.google.com/console'>Google API console</a>
      </h3>";
  } else {
    $ret = "Warning: You need to set Client ID, Client Secret and Redirect URI from the";
    $ret .= " Google API console:\nhttp://developers.google.com/console\n";
  }
  return $ret;
}

function missingServiceAccountDetailsWarning()
{
  $ret = "";
  if (isWebRequest()) {
    $ret = "
      <h3 class='warn'>
        Warning: You need to set Client ID, Email address and the location of the Key from the
        <a href='http://developers.google.com/console'>Google API console</a>
      </h3>";
  } else {
    $ret = "Warning: You need to set Client ID, Email address and the location of the Key from the";
    $ret .= " Google API console:\nhttp://developers.google.com/console\n";
  }
  return $ret;
}

/**
* Creates a link where the user can allow the server to access the Service API.
* The authorization URL is created by the Google_Client.
* @authUrl - Authorization URL
*/
function missingAccessTokenLink($authUrl) {
    return sprintf("<p><b>Authorization missing or expired</b></p>
    <p>You need to allow this server to use 
    your Lifelog account.</p><a class='login' href='%s'>
    Add my Lifelog account</a>", $authUrl);
}

function logoutLink() {
    return '<a class="login" href="index.php?logout">Log out</a>';
}

function revokeTokenLink() {
    return '<a class="login" href="index.php?revoke">Revoke token</a>';
}

?>
