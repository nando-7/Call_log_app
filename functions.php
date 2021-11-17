<?php

class Functions {

    public static function header_html() {
        ?>
            <html>
            <head>
            <title>Call log app</title>

            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

            <style type="text/css">
            body {
                background:#77E894;
            }
            </style>
            </head>
            <body>
         <?php

    }

    public static function bottom_html() {
        ?>
            </body>
            </html>
        <?php
    }


    public static function connection() {
        $conn = mysqli_connect('localhost','root','YOUR_SQL_PASSWORD','call_log_app');

        if(!$conn) {
            die("connection failed!");
        }

        return $conn;
    }

    public static function convertToHoursMins($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function get_time_spent($id, $header_hours, $header_minutes) {

        $conn=self::connection();

        $query_minutes_details='select sum(minutes) as minutes_sum, sum(hours) as hours_sum from call_details where call_id='.$id;

        $minutes_details=mysqli_query($conn,$query_minutes_details);

        $query_hours_details='select sum(hours) as hours_sum from call_details where call_id='.$id;

        $hours_details=mysqli_query($conn,$query_hours_details);

        $mins_header_plus_mins_details=$header_minutes+mysqli_fetch_assoc($minutes_details)['minutes_sum'];

        $hours_to_minutes=($header_hours+mysqli_fetch_assoc($hours_details)['hours_sum'])*60;

        $all_minutes=$mins_header_plus_mins_details+$hours_to_minutes;


        $all_mins_to_hours=self::convertToHoursMins($all_minutes);

        return $all_mins_to_hours;
    }

}


?>

