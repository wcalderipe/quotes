<?php

namespace AppBundle\EventListener\Subdomain;

class RequestParser
{
    protected $whiteList = array(
        'www',
    );

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function getSubdomain()
    {
        $host    = $this->request->server->get('HTTP_HOST');
        $regex   = '/(?:http[s]*\\:\\/\\/)*(.*?)\\.(?=[^\\/]*\\..{2,5})/i';
        $matches = null;

        preg_match($regex, $host, $matches);

        if (in_array($matches[1], $this->whiteList)) {
            return null;
        }

        return empty($matches) ? null : $matches[1];
    }
}