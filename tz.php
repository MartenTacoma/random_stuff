<?php

/**
 * Simple timezone table
 * Author: Marten Tacoma
 * 
 * Add timezones and labels to array below in form label=>time zone identifier
 * Set night start and end as desired
 * Set timestep in hours as desired
 * 
 * Run from a php capable web server, access with tz.php[?d=YYYY-mm-dd], if d parameter is ommitted table is shown for current date (server date)
 */

$zones = [
    'Western Europe'=>'Europe/Amsterdam',
    'UK'=>'Europe/London',
    'America East'=>'America/New_york',
    'America West'=>'America/Los_Angeles',
    'Hobart'=>'Australia/Hobart',
    'Tokyo'=>'Asia/Tokyo',
    'India'=>'Asia/Kolkata'
];

$night_start = '22:00';//H:i
$night_end = '7:00';//H:i
$timestep = 1;//hours
?>

<html><head><title>Timezone table</title><style>
td, th {
    text-align: center;
    padding: 1px 5px;
}
td.night {
    background: #666;
    color: white;
}
tr:nth-child(odd){
    background-color: #ddd;
}
</style>
<head><body>
<?php

$d = $_GET['d'] ?? date('Y-m-d');
echo 'Table for '.$d;
echo '<table><thead><tr><th>UTC</th>';
foreach($zones as $zone=>$id){
    echo '<th>'.$zone.'</th>';
}
echo '</tr></thead><tbody>';

for($opt=0;$opt<24;$opt+=$timestep){
    if(fmod($opt,1) === 0){
        $ts = $opt.':00';
    } else {
        $ts = floor($opt).':'.(fmod($opt,1)*60);
    }
    $t = new DateTime($d.' '.$ts, new DateTimeZone('UTC'));
    $d = $t->format('Ymd');
    echo '<tr><th>'.$t->format('H:i').'</th>';
    foreach($zones as $zone=>$id){
        $t->setTimezone(new DateTimeZone($id));
        $dt = $t->format('Ymd');
        echo '<td '
        . (($t->format('Hi') < str_replace(':', '', $night_end) || $t->format('Hi') >= str_replace(':', '', $night_start)) ? 'class="night"' : '')
        . '>'.$t->format('H:i')
        . ($dt < $d 
            ? ' (-1)' 
            : ($dt > $d 
                ? ' (+1)'
                : ''))
        . '</td>';
    }
    echo '</tr>';
}
echo '</tbody></table>';
?>
</body></html>
