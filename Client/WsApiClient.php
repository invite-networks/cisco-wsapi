<?php
/*
 * This file is part of the Invite Wsapi Library
 * 
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Component\Cisco\Wsapi\Client;

/**
 * INVITE Cisco IOS WS API Soap Client
 *
 * @category   API
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class WsApiClient extends \SoapClient
{

    /**
     * Cisco UC Gateway API Provider namespace injected from calling method.
     *
     * @var type
     */
    protected $namespace;

    /**
     * Override SOAP client then call.
     *
     * Had to get creative with this one. The controller must have the added
     * "namespace" var, then take it out and call the SOAP Client parent.
     *
     * @param type $wsdl
     * @param type $options
     * @param type $namespace
     */
    function __construct($wsdl, $options, $namespace)
    {
        parent::__construct($wsdl, $options);

        $this->namespace = $namespace;
    }

    /**
     * Override PHP SOAP Client __doRequest call to regex Cisco UC Gateway API XML data.
     *
     * NOTE: Leave the SOAP debug commands because they intercept the call better here.
     *
     * @param type $request
     * @param type $location
     * @param type $action
     * @param type $version
     * @param int $oneWay
     * @return type
     */
    function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        $request1 = preg_replace('/<ns1:(\w+)/', '<$1 xmlns="' . $this->namespace . '"', $request, 1);
        $request2 = preg_replace('/<ns1:(\w+)/', '<$1', $request1);
        $request3 = str_replace(array('/ns1:', 'xmlns:ns1="' . $this->namespace . '"'), array('/', ''), $request2);

        $response = parent::__doRequest($request3, $location, $action, $version, $oneWay = 0);

        // ******* SOAP DEBUG COMMANDS **************
        // echo "REQUEST HEADERS:\n<br>" . parent::__getLastRequestHeaders() . "\n<br>";
        // echo "REQUEST:\n<br>" . parent::__getLastRequest() . "\n<br>";
        // echo "RESPONSE HEADERS:\n<br>" . parent::__getLastResponseHeaders() . "\n<br>";
        // echo "RESPONSE:\n<br>" . parent::__getLastResponse() . "\n<br>";
        //var_dump(parent::__getFunctions());

        return ($response);
    }

}
