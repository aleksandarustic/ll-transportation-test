<?php 
require_once('./config.php');
require_once './vendor/autoload.php';


class App {

    private $Client;

    public static function create_client(){

        $client = new Google_Client();
        $client->setAuthConfig("credentials.json");
        $client->setApplicationName("ll-transportation-test");
        $client->setRedirectUri(REDIRECT_URL);
        $client->setAccessType('offline');
        $client->addScope("https://www.googleapis.com/auth/calendar");

        return $client;
    }

    public static function check_if_token_file_exists(&$client){
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }
        else{
            $authUrl = $client->createAuthUrl();
            header("Location:".$authUrl);  
        }
    }


    public static function check_if_token_file_expired(&$client){
     // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                header("Location:".$authUrl);    
            }
        
        }
    }


    public static function create_new_token($authCode){
        
        $tokenPath = 'token.json';

        $client = self::create_client();

        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }
    
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
    
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));

    
    }



}