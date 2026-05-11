BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
PRODID:{{ config('app.name') }}
TZID:Europe/Berlin
BEGIN:VEVENT
UID:{{ $event['uid'] }}
DTSTAMP:{{ $event['now'] }}
LOCATION:{{ $event['room'] }}
SUMMARY:{{ $event['committeeName'] }}
DTSTART;TZID={{ $event['timezone'] }}:{{ $event['start'] }}
DTEND;TZID={{ $event['timezone'] }}:{{ $event['end'] }}
END:VEVENT
END:VCALENDAR
