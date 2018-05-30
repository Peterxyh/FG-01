<?php
namespace App\Services;
/**
 * Created by PhpStorm.
 * User: Brian Bi
 * Date: 2018/5/29
 * Time: 17:53
 */

use Goutte;

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
        // return Goutte::request($this->_method, $this->_targetUrl);
        $s = file_get_contents($this->_targetUrl);
        preg_match_all("/Core\.pageData\('match[\s\S]*\]\]\}\}\);/", $s, $m);

        print_r($m);
    }
}