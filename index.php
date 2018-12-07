<?php 
include 'src/Facebook/autoload.php'; // path to your autoload.php
$fb = new Facebook\Facebook([
  'app_id' => '513784435767022', // Replace {app-id} with your app id
  'app_secret' => 'b946d5e8b7ef40fce506ea12b3e9dde0',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://localhost/php-graph-sdk', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';