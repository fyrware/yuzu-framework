<?php

class Yz_Calendar_Service {

    public function get_google_calendar_event_invite_url(array $options): string {
        $title       = yz()->tools->get_value($options, "title");
        $description = yz()->tools->get_value($options, "description");
        $location    = yz()->tools->get_value($options, "location");
        $start_time  = yz()->tools->get_value($options, "start_time");
        $end_time    = yz()->tools->get_value($options, "end_time");

        return "https://www.google.com/calendar/render?action=TEMPLATE"
            . "&text=" . urlencode($title)
            . "&details=" . urlencode($description)
            . "&location=" . urlencode($location)
            . "&dates=" . urlencode($start_time) . "/" . urlencode($end_time);
    }

    public function create_ics_file(array $options): string {
        $title       = yz()->tools->get_value($options, "title");
        $description = yz()->tools->get_value($options, "description");
        $location    = yz()->tools->get_value($options, "location");
        $start_time  = yz()->tools->get_value($options, "start_time");
        $end_time    = yz()->tools->get_value($options, "end_time");

        $title = str_replace("\n", "\\n", $title);
        $description = str_replace("\n", "\\n", $description);
        $location = preg_replace("/\r\n|\n/", " ", $location);

        return "BEGIN:VCALENDAR\n"
            . "VERSION:2.0\n"
            . "PRODID:-//hacksw/handcal//NONSGML v1.0//EN\n"
            . "BEGIN:VEVENT\n"
            . "UID:" . uniqid() . "\n"
            . "DTSTAMP:" . gmdate("Ymd\THis\Z") . "\n"
            . "DTSTART:" . gmdate("Ymd\THis\Z", strtotime($start_time)) . "\n"
            . "DTEND:" . gmdate("Ymd\THis\Z", strtotime($end_time)) . "\n"
            . "SUMMARY:" . $title . "\n"
            . "DESCRIPTION:" . $description . "\n"
            . "X-ALT-DESC;FMTTYPE=text/html:" . "<!doctype html><html><body>". $description . "</body></html>\n"
            . "LOCATION:" . $location . "\n"
            . "END:VEVENT\n"
            . "END:VCALENDAR";
    }
}