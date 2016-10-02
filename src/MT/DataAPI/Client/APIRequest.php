<?php

namespace MT\DataAPI\Client;

use \GuzzleHttp\Client;

class APIRequest
{
    private $endpoint;
    private $guzzle;

    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->guzzle = new Client();
    }

    public function send($accessToken, $args)
    {
        $request = $this->request($accessToken, $args);
        return $this->guzzle->send($request);
    }

    private function request($accessToken, $args)
    {
        $url = $this->endpoint->requestUrl($args);
        $options = $this->options($accessToken, $args);
        return $this->guzzle->createRequest(
            $this->endpoint->verb,
            $url,
            $options
        );
    }

    private function options($accessToken, $args)
    {
        $options = array();
        if ($accessToken) {
            $options['headers'] = array(
            'X-MT-Authorization' => "MTAuth accessToken=$accessToken"
            );
        }
        if ($this->isPostOrPut()) {
            $options['form_options'] = array('form_params' => $args);
        }
        return $options;
    }

    private function isPostOrPut()
    {
        return in_array($this->endpoint->verb, array('POST', 'PUT'));
    }
}
