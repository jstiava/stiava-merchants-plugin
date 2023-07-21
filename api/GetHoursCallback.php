<?php

namespace StiavaMerchantsApp\API;
use StiavaMerchantsApp\Includes\Endpoint;
use StiavaMerchantsApp\Includes\Schedule;

class GetHoursCallback extends Endpoint
{
    public function __construct()
    {
        $url = '/hours_diagnostic';
        $method = 'GET';
        $callback = array($this, 'request_callback');
        $params = [
            'string' => [
                'required' => true,
                'type' => 'string',
            ]
        ];

        parent::__construct($url, $method, $callback, $params);
    }

    public function request_callback($request)
    {
        return $this->implement($request);
    }

    private function implement($request)
    {

        $schedule = new Schedule($request['string']);
        $clean = $schedule->clean_string($request['string']);
        $strings = preg_split("/(?:;|\n)+/", $clean);
        
        return [
            "input" => $request['string'],
            "clean" => $clean,
            "strings" => $strings,
            "object" => $schedule,
            "abstraction" =>$schedule->hours_abstractor(),
        ];

    }

    public static function append()
    {
        $endpoint = new GetHoursCallback();
        $endpoint->register();
    }

}