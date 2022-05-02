<?php

require_once('Facebook/autoload.php');

$fb = new Facebook\Facebook([
    'app_id' => '',
    'app_secret' => '',
    'default_graph_version' => 'v2.9',
]);

$helper = $fb->getRedirectLoginHelper();
