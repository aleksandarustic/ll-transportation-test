<?php

require_once('./classes/App.php');

$message = "";
$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = $_POST['email'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $note = $_POST['note'];

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $note = filter_var($note, FILTER_SANITIZE_STRING);

    $datetime = new Datetime($_POST['date'] .' '. $_POST['time'],new DateTimeZone(TIMEZONE));
    $datetime_formated = $datetime->format(\DateTime::RFC3339);
    $datetime_end = $datetime;
    date_modify($datetime_end,"+30 minutes");
    $datetime_end_formated = $datetime_end->format(\DateTime::RFC3339);

    $client = App::create_client();
    App::check_if_token_file_exists($client);
    App::check_if_token_file_expired($client);

    $service = new Google_Service_Calendar($client);


     $event = new Google_Service_Calendar_Event(array(
         'summary' => $name,
         'location' => 'Beograd',
         'description' => $note,
         'visibility' => 'public',
         'sendUpdates' => 'all',
         'sendNotifications' => true, 
         'start' => array(
           'dateTime' => $datetime_formated,
           'timeZone' => TIMEZONE,
         ),
         'end' => array(
           'dateTime' => $datetime_end_formated,
           'timeZone' => TIMEZONE,
         ),
         'attendees' => array(
           array('email' => $email),
         ),
         'reminders' => array(
           'useDefault' => FALSE,
           'overrides' => array(
             array('method' => 'email', 'minutes' => 30),
             array('method' => 'email', 'minutes' => 15),
           ),
         ),
       ));
       $sendNotifications = array('sendNotifications' => true);


     $event = $service->events->insert(CALENDAR_ID, $event,$sendNotifications);
     $message = "Google calendar event has been successfuly created!";


    // $optParams = array(
    //     'maxResults' => 10,
    //     'orderBy' => 'startTime',
    //     'singleEvents' => true,
    //     'timeMin' => date('c'),
    // );

    // $results = $service->events->listEvents(CALENDAR_ID);
    // $events = $results->getItems();
    // if (empty($events)) {
    //     print "No upcoming events found.\n";
    // } else {
    //     print "Upcoming events:\n";
    //     foreach ($events as $event) {
    //         $start = $event->start->dateTime;
    //         if (empty($start)) {
    //             $start = $event->start->date;
    //         }
    //         printf("%s (%s)\n", $event->getSummary(), $start);
    //     }
    // }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>gCalendar test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
     integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
 
    <div class="container">
        <div class="message-wrapper">
            <?php
                if(isset($message) && $message != ''){
                    if($error != ''){
                        echo '<div class="alert alert-danger">'.$message.' </>';
                    }
                    else{
                        echo '<div class="alert alert-success">'.$message.' </>';
                    }
                }
            ?>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3 class="card-title text-center">Add New Calendar Event</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" class="form">
                            <div class="form-group required">
                                <label for="name">Name</label>
                                <input type="text"  class="form-control" id="name" name="name" placeholder="Enter name">
                            </div>
                            <div class="form-group required">
                                <label for="phone">Phone</label>
                                <input type="text"  class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                            </div>
                            <div class="form-group required">
                                <label for="email">Email address</label>
                                <input type="email"  class="form-control" id="email" name="email" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="note">Note:</label>
                                <textarea class="form-control" rows="2" name="note" id="note"></textarea>
                            </div>
                            
                            <div class="form-group required">
                                 <label for="time">Date:</label>
                                 <input type="text" name="date" class="form-control datetimepicker-input" id="date" data-toggle="datetimepicker" data-target="#date"/>
                            </div>
                            <div class="form-group required">
                                 <label for="time">Time:</label>
                                 <input type="text" name="time" class="form-control datetimepicker-input" id="time" data-toggle="datetimepicker" data-target="#time"/>
                            </div>

                            <div class="form-group required">
                                <div class="form-row">
                                    <div class="col-2">
                                        <span id="captcha"></span>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="captcha" class="captcha form-control" maxlength="4" size="4" placeholder="Enter captcha code" tabindex=3 />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-submit" type="submit" name="create_event"> Create </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="./js/validate.js"></script>

</html>