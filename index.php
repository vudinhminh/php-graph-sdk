<?php

include 'src/Facebook/autoload.php'; // path to your autoload.php
$app_id     = "513784435767022";
$app_secret = "b946d5e8b7ef40fce506ea12b3e9dde0";
$my_url     = "http://localhost/php-graph-sdk";

$fb = new Facebook\Facebook([
    'app_id'                => $app_id, // Replace {app-id} with your app id
    'app_secret'            => $app_secret,
    'default_graph_version' => 'v2.2',
        ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl    = $helper->getLoginUrl($my_url, $permissions);
echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';


session_start();
$code = isset($_REQUEST['state']) ? $_REQUEST["code"] : NULL;

if (empty($code))
{
    $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $dialog_url        = "http://www.facebook.com/dialog/oauth?client_id="
            . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
            . $_SESSION['state'];

    echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
else
{
    $_SESSION['state'] = $_REQUEST["state"];
}

if (isset($_REQUEST['state']) && isset($_SESSION['state']) && $_REQUEST['state'] == $_SESSION['state'])
{
    $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
            . "&client_secret=" . $app_secret . "&code=" . $code;

    $response = @file_get_contents($token_url);

    $params = json_decode($response, true);

    $graph_url = "https://graph.facebook.com/me?access_token="
            . $params['access_token'];

    $user = json_decode(file_get_contents($graph_url));
    echo "<pre style='color:Red'><hr/> File:" . __FILE__ . "<br/>";
    var_dump($user);
    echo "<br/>Line: " . __LINE__ . "<hr/></pre>";
    echo("Hello " . $user->name);
}
else
{
    echo("The state does not match. You may be a victim of CSRF.");
}
?>