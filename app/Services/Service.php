<?php


namespace App\Services;


use App\Traits\ApiResponser;
use DateTime;

class Service
{
    use ApiResponser;
//    protected $serviceGateway;

    /**
     * Service constructor.
     */

//    public function __construct(ServiceGateway $serviceGateway)
//    {
//        $this->serviceGateway = $serviceGateway;
//    }

    public function TimeIsBetweenTwoTimes($from, $till, $input) {
        $f = DateTime::createFromFormat('H:i:s', $from);
        $t = DateTime::createFromFormat('H:i:s', $till);
        $i = DateTime::createFromFormat('H:i:s', $input);
        if ($f > $t) $t->modify('+1 day');
        return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
    }

    public function check_time_overlap($start_time1, $end_time1, $start_time2, $end_time2) {
        return (($start_time1) <=  ($end_time2) && ($start_time2) < ($end_time1) ? true : false);
    }
}
