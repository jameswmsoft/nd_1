<?php

class Calendar
{
    var $events;

    function Calendar($date)
    {
        if(empty($date)) $date = time();
        define('NUM_OF_DAYS', date('t',$date));
        define('CURRENT_DAY', date('j',$date));
        define('CURRENT_MONTH_A', date('F',$date));
        define('CURRENT_MONTH_N', date('n',$date));
        define('CURRENT_YEAR', date('Y',$date));
        define('START_DAY', (int) date('N', mktime(0,0,0,CURRENT_MONTH_N,1, CURRENT_YEAR)) - 1);
        define('COLUMNS', 7);
        define('PREV_MONTH', $this->prev_month());
        define('NEXT_MONTH', $this->next_month());
        $this->events = array();
    }

    function prev_month()
    {
        return mktime(0,0,0,
            (CURRENT_MONTH_N == 1 ? 12 : CURRENT_MONTH_N - 1),
            (checkdate((CURRENT_MONTH_N == 1 ? 12 : CURRENT_MONTH_N - 1), CURRENT_DAY, (CURRENT_MONTH_N == 1 ? CURRENT_YEAR - 1 : CURRENT_YEAR)) ? CURRENT_DAY : 1),
            (CURRENT_MONTH_N == 1 ? CURRENT_YEAR - 1 : CURRENT_YEAR));
    }

    function next_month()
    {
        return mktime(0,0,0,
            (CURRENT_MONTH_N == 12 ? 1 : CURRENT_MONTH_N + 1),
            (checkdate((CURRENT_MONTH_N == 12 ? 1 : CURRENT_MONTH_N + 1) , CURRENT_DAY ,(CURRENT_MONTH_N == 12 ? CURRENT_YEAR + 1 : CURRENT_YEAR)) ? CURRENT_DAY : 1),
            (CURRENT_MONTH_N == 12 ? CURRENT_YEAR + 1 : CURRENT_YEAR));
    }

    function getEvent($timestamp)
    {
        $event = NULL;
        if(array_key_exists($timestamp, $this->events))
            $event = $this->events[$timestamp];
        return $event;
    }

    function addEvent($event, $day = CURRENT_DAY, $month = CURRENT_MONTH_N, $year = CURRENT_YEAR)
    {
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        if(array_key_exists($timestamp, $this->events))
            array_push($this->events[$timestamp], $event);
        else
            $this->events[$timestamp] = array($event);
    }

    function makeEvents()
    {
        if($events = $this->getEvent(mktime(0, 0, 0, CURRENT_MONTH_N, CURRENT_DAY, CURRENT_YEAR)))
            foreach($events as $event) echo $event.'<br />';
    }

    function print__r($data){
        print "<pre>";
        print_r($data);
        print "</pre>";
    }

    function makeCalendar()
    {
        global $link;

        $day = CURRENT_DAY;
        $month = CURRENT_MONTH_N;
        $year = CURRENT_YEAR;

        $prev_lastday = date('t',$this->prev_month());
        $prev_month = date('n',$this->prev_month());
        $prev_year = date('Y',$this->prev_month());

        $next_lastday = date('t',$this->next_month());
        $next_month = date('n',$this->next_month());
        $next_year = date('Y',$this->next_month());

        echo '<table border="1" cellspacing="4" id="calendar"><tr id="cal_title">';
        echo '<th colspan="6" width="30" style="padding-left: 20px;border-right: none;border-bottom: 3px solid #DDDDDD;"><a href="?date=' . PREV_MONTH . '" style="color: #1F77D0">&lt;</a><span colspan="5" style="text-align:left;color: #333333;margin-left: 50px;font-weight: lighter">' . CURRENT_MONTH_A . ' &nbsp' . CURRENT_YEAR . '</span></th>';
        echo '<th width="30" style="text-align: right;padding-right: 20px;border-left: none;border-bottom: 3px solid #DDDDDD""><a href="?date=' . NEXT_MONTH . '" style="color: #1F77D0;">&gt;</a></th>';
        echo '</tr><tr id="cal_header">';
        echo '<td width="30" class="cal_weekday">Monday</td>';
        echo '<td width="30" class="cal_weekday">Tuesday</td>';
        echo '<td width="30" class="cal_weekday">Wednesday</td>';
        echo '<td width="30" class="cal_weekday">Thursday</td>';
        echo '<td width="30" class="cal_weekday">Friday</td>';
        echo '<td width="30" class="cal_weekday">Saturday</td>';
        echo '<td width="30" class="cal_weekday">Sunday</td>';
        echo '</tr><tr style="height: 120px">';

        $sql = "SELECT *, DAY(apt_time) aDay, MONTH(apt_time) aMonth, YEAR(apt_time) aYear FROM `appointments` WHERE ((YEAR(apt_time) = {$year} AND MONTH(apt_time) = {$month})
                OR (YEAR(apt_time) = {$prev_year} AND MONTH(apt_time) = {$prev_month}) OR (YEAR(apt_time) = {$next_year} AND MONTH(apt_time) = {$next_month})) AND user_id='".$_SESSION['user_id']."'";

        $res = mysqli_query($link, $sql);

        $rowsAry = array();

        if(mysqli_num_rows($res)) {
            while ($row = mysqli_fetch_assoc($res)) {
                $rowsAry[] = $row;
            }
        }

        $aptsPerDay = array();
        $aptsLastDay = array();
        $aptsNextDay = array();

        $rows = 1;$month_days = 0;

        if(NUM_OF_DAYS>$prev_lastday){
            $month_days = NUM_OF_DAYS;
        }else{
            $month_days = $prev_lastday;
        }

        for ($i =1; $i <= $month_days; $i++){
            $ary = array(); $pre_ary = array(); $next_ary = array();
            for ($j = 0; $j < count($rowsAry); $j++){
                if (($rowsAry[$j]['aDay'] == $i) AND ($rowsAry[$j]['aMonth'] == $month) AND ($rowsAry[$j]['aYear'] == $year)){
                    $ary[] = $rowsAry[$j];
                }
            }

            for ($pj = 0; $pj <= count($rowsAry); $pj++){
                if (($rowsAry[$pj]['aDay'] == $i) AND ($rowsAry[$pj]['aMonth'] == $prev_month) AND ($rowsAry[$pj]['aYear'] == $prev_year)){
                    $pre_ary[] = $rowsAry[$pj];
                }
            }

            for ($nj = 0; $nj <= count($rowsAry); $nj++){
                if (($rowsAry[$nj]['aDay'] == $i) AND ($rowsAry[$nj]['aMonth'] == $next_month ) AND ($rowsAry[$nj]['aYear'] == $next_year)){
                    $next_ary[] = $rowsAry[$nj];
                }
            }

            $aptsPerDay[$i] = $ary;
            $aptsLastDay[$i] = $pre_ary;
            $aptsNextDay[$i] = $next_ary;

        }

        for ($prev=$prev_lastday-START_DAY + 1;$prev<=$prev_lastday;$prev++){

            echo '<td ><div style="overflow: auto;height: 120px;color: #b2b2b2;">';
            echo '<div class="cal_itemday">'.($prev).'</div>';

            $aptsLastAry = $aptsLastDay[$prev];

            if (count($aptsLastAry) == 0){
                echo '<div style="width: 100%;height: 85px;cursor: pointer" onclick="window.location=\'add_apts.php\'"></div>';
            }

            foreach($aptsLastAry as $aptLast){

                echo '<div class="cal_appoint_title"><a href="edit_apt.php?id='.$aptLast['id'].'"><div class="item_appoint">'.$aptLast['title'].'</div></a><div style="width: 10%;float: right"><i class="fa fa-trash-o remove_icon" onclick="deleteApt('.$aptLast['id'].')"></i></div></div>';

            }

            echo '</td>';

        }

        for ($i = 1; $i <= NUM_OF_DAYS; $i++) {

            echo '<td style="width: 13.5%;"><div style="overflow: auto;height: 120px;background-color: #f5f5f5">';

            echo '<div class="cal_itemday">'.$i.'</div>';

            $aptsAray = $aptsPerDay[$i];

            if (count($aptsAray) == 0){
                echo '<div style="width: 100%;height: 85px;cursor: pointer" onclick="window.location=\'add_apts.php\'"></div>';
            }

            foreach($aptsAray as $apt){

                echo '<div class="cal_appoint_title"><a href="edit_apt.php?id='.$apt['id'].'"><div class="item_appoint">'.$apt['title'].'</div></a><div style="width: 10%;float: right"><i class="fa fa-trash-o remove_icon" onclick="deleteApt('.$apt['id'].')"></i></div></div>';

            }

            echo '</td>';

            if ((($i + START_DAY) % COLUMNS) == 0 && $i != NUM_OF_DAYS) {
                echo '</tr><tr>';
                $rows++;
            }
        }

        for ($n=1;$n<=(COLUMNS * $rows) - (NUM_OF_DAYS + START_DAY);$n++){

            echo '<td ><div style="overflow: auto;height: 120px;color: #b2b2b2;">';
            echo '<div class="cal_itemday">'.($n).'</div>';

            $aptsNextAry = $aptsNextDay[$n];

            if (count($aptsNextAry) == 0){
                echo '<div style="width: 100%;height: 85px;cursor: pointer" onclick="window.location=\'add_apts.php\'"></div>';
            }

            foreach($aptsNextAry as $aptNext){

                echo '<div class="cal_appoint_title"><a href="edit_apt.php?id='.$aptNext['id'].'"><div class="item_appoint">'.$aptNext['title'].'</div></a><div style="width: 10%;float: right"><i class="fa fa-trash-o remove_icon" onclick="deleteApt('.$aptNext['id'].')"></i></div></div>';

            }

            echo '</td>';
        }

    }
}

$cal = new Calendar($_GET['date']);
$cal->makeCalendar();
$cal->makeEvents();
?>