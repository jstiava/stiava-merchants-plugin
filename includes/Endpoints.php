<?php

namespace StiavaMerchantsApp\Includes;
use StiavaMerchantsApp\API\GetMerchantsEndpoint;
use StiavaMerchantsApp\API\GetPostsWithMetaEndpoint;
use StiavaMerchantsApp\API\PostAllMerchantsEndpoint;
use StiavaMerchantsApp\API\PostNewHours;
use StiavaMerchantsApp\API\PostNewComponentWithMeta;
use StiavaMerchantsApp\API\GetHoursCallback;
use StiavaMerchantsApp\API\GetHours;
use StiavaMerchantsApp\API\GetClosedDays;
use StiavaMerchantsApp\API\PostNewClosure;
use StiavaMerchantsApp\API\UpdateHours;
use StiavaMerchantsApp\API\DeleteHours;
use StiavaMerchantsApp\API\PostNewVenue;
use StiavaMerchantsApp\API\GetVenues;
use StiavaMerchantsApp\API\GetMenuItems;
use StiavaMerchantsApp\API\UpdateColor;

class Endpoints
{
    public function run()
    {
        GetMerchantsEndpoint::append();
        GetPostsWithMetaEndpoint::append();
        PostAllMerchantsEndpoint::append();
        PostNewHours::append();
        PostNewComponentWithMeta::append();
        GetHoursCallback::append();
        GetHours::append();
        GetClosedDays::append();
        PostNewClosure::append();
        UpdateHours::append();
        DeleteHours::append();
        PostNewVenue::append();
        GetVenues::append();
        GetMenuItems::append();
        UpdateColor::append();
    }
}


class Endpoint
{
    protected $url;
    protected $method;
    protected $callback;
    protected $params;

    public function __construct($url, $method, $callback, $params)
    {
        $this->url = $url;
        $this->method = $method;
        $this->callback = $callback;
        $this->params = $params;
    }

    public function register()
    {
        add_action('rest_api_init', function () {
            register_rest_route('jsmp/v1', $this->url, array(
                'methods' => $this->method,
                'callback' => $this->callback,
                'permission_callback' => $this->permissions_callback(),
                'args' => $this->params
            )
            );
        });
    }

    public function permissions_callback()
    {
        return;
    }

}