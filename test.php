<?php 

require_once('./config.php');
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig("credentials.json");
$client->setApplicationName("ll-transportation-test");
$client->setRedirectUri(REDIRECT_URL);
$client->setAccessType('offline');
$client->addScope("https://www.googleapis.com/auth/calendar");

$tokenPath = 'token.json';
if (file_exists($tokenPath)) {
    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);
}
elseif(isset($_GET['code'])){

    $authCode = $_GET['code'];
    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    $client->setAccessToken($accessToken);

    // Check to see if there was an error.
    if (array_key_exists('error', $accessToken)) {
        throw new Exception(join(', ', $accessToken));
    }

      // Save the token to a file.
    if (!file_exists(dirname($tokenPath))) {
        mkdir(dirname($tokenPath), 0700, true);
    }

    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}

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

$service = new Google_Service_Calendar($client);


// $event = new Google_Service_Calendar_Event(array(
//     'summary' => 'Google I/O 2015',
//     'location' => '800 Howard St., San Francisco, CA 94103',
//     'description' => 'A chance to hear more about Google\'s developer products.',
//     'start' => array(
//       'dateTime' => '2015-05-28T09:00:00-07:00',
//       'timeZone' => 'America/Los_Angeles',
//     ),
//     'end' => array(
//       'dateTime' => '2015-05-28T17:00:00-07:00',
//       'timeZone' => 'America/Los_Angeles',
//     ),
//     'recurrence' => array(
//       'RRULE:FREQ=DAILY;COUNT=2'
//     ),
//     'attendees' => array(
//       array('email' => 'lpage@example.com'),
//       array('email' => 'sbrin@example.com'),
//     ),
//     'reminders' => array(
//       'useDefault' => FALSE,
//       'overrides' => array(
//         array('method' => 'email', 'minutes' => 24 * 60),
//         array('method' => 'popup', 'minutes' => 10),
//       ),
//     ),
//   ));

// $event = $service->events->insert(CALENDAR_ID, $event);

// Print the next 10 events on the user's calendar.
$optParams = array(
  'maxResults' => 10,
  'orderBy' => 'startTime',
  'singleEvents' => true,
  'timeMin' => date('c'),
);

$results = $service->events->listEvents(CALENDAR_ID);
$events = $results->getItems();
if (empty($events)) {
    print "No upcoming events found.\n";
} else {
    print "Upcoming events:\n";
    foreach ($events as $event) {
        $start = $event->start->dateTime;
        if (empty($start)) {
            $start = $event->start->date;
        }
        printf("%s (%s)\n", $event->getSummary(), $start);
    }
}