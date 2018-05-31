<?php
namespace App\Services;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 17:53
 */

use GuzzleHttp\Client;

class Reptile
{
    /** @var $_targetUrl */
    protected $_targetUrl = '';

    /** @var $_method */
    protected $_method    = 'GET';


    public function __construct($_targetUrl, $_method = 'GET')
    {
        $this->_targetUrl   = $_targetUrl;
        $this->_method      = $_method;
    }

    /**
     * request
     */
    public function request()
    {
        $http = new Client();
        $response = $http->get($this->_targetUrl);

        if ($response)
        {
            return \GuzzleHttp\json_decode($response, true);
        }

        return false;
    }
}