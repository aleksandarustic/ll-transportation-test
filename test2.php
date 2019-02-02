<?php 

require_once('./classes/App.php');

$client = App::create_client();
App::check_if_token_file_exists($client);
App::check_if_token_file_expired($client);

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