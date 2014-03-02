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
namespace Invite\Component\Cisco\Wsapi\Server;

use Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface;
use Invite\Component\Cisco\Wsapi\Handler\XcdrHandler;

/**
 * XcdrServer.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class XcdrServer
{

    /**
     * @var \Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface
     */
    protected $xcdrListener;

    /**
     * Xcdr Soap Handler construct.
     * 
     * @param \Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface $xcdrListener
     */
    public function __construct(XcdrListenerInterface $xcdrListener)
    {
        $this->xcdrListener = $xcdrListener;
    }

    /**
     * Xcdr Soap Webservice method.
     */
    public function processXcdr()
    {
        $schema = XcdrHandler::XCDR_SCHEMA;

        ini_set("soap.wsdl_cache_enabled", "0");

        $soapServer = new \SoapServer(null, array(
            'uri' => $schema,
            'soap_version' => SOAP_1_2,
        ));

        $soapServer->setObject(new XcdrHandler($this->xcdrListener));

        try {
            ob_start();
            $soapServer->handle();
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            );
        }

        $result = ob_get_clean();
        $result1 = preg_replace('/<ns1:(\w+)/', '<$1 xmlns="' . $schema . '"', $result, 1);
        $result2 = preg_replace('/<\/ns1:(\w+)/', '</$1', $result1);
        $result3 = preg_replace('/<rpc:result.*rpc:result>/', '', $result2);
        $result4 = preg_replace('/SolicitXcdrProbingResponse/', 'ResponseXcdrProbing', $result3);
        $result5 = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $result4);
        $result6 = preg_replace('/<env:Envelope(?s).*<env:Body(?s).*soap-rpc">/', '<soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope"><soapenv:Header/><soapenv:Body>', $result5);
        $result7 = preg_replace('/<\/env:Envelope>/', '</soapenv:Envelope>', $result6);
        $result8 = preg_replace('/<\/env:Body>/', '</soapenv:Body>', $result7);
        $result9 = preg_replace('/\senv:encodingStyle.*soap-encoding"/', '', $result8);

        return array(
            'status' => 'success',
            'result' => $result9
        );
    }

}
