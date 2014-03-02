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

use Invite\Component\Cisco\Wsapi\Client\WsApiClient;
use Invite\Component\Cisco\Wsapi\Handler\XcdrHandler;

/**
 * INVITE Cisco WsApi XCDR Client
 *
 * @category   CDR
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class XcdrClient
{

    /**
     * @var \Invite\Component\Cisco\Wsapi\Soap\Client\WsApiSoapClient
     */
    protected $soapClient;

    /**
     * Cisco IOS XCDR Provider Registration
     */
    public function requestXcdrRegister($host, $appUrl, array$options = array())
    {
        $protocol = array_key_exists('protocol', $options) ? $options['protocol'] : 'http';
        $url = $protocol . '://' . $host . ':8090/cisco_xcdr';
        $schema = XcdrHandler::XCDR_SCHEMA;
        $appName = array_key_exists('appName', $options) ? $options['appName'] : 'invite_xcdr';
        $transactionId = array_key_exists('transactionId', $options) ? $options['transactionId'] : uniqid('xcdr');

        $socket = array_key_exists('socket', $options) ? $options['socket'] : 5;
        ini_set('default_socket_timeout', $socket);

        $connection = array_key_exists('connection', $options) ? $options['connection'] : 5;
        $trace = array_key_exists('trace', $options) ? $options['trace'] : false;
        $exception = array_key_exists('exception', $options) ? $options['exception'] : false;

        $this->soapClient = new WsApiClient(null, array(
            "location" => $url,
            "uri" => $schema,
            "soap_version" => SOAP_1_2,
            "connection_timeout" => $connection,
            "trace" => $trace,
            "exception" => $exception
                ), $schema // Used to send API namespace to SOAP Client
        );

        $soapObjectXML = '<applicationData>
                                <name>' . $appName . '</name>
                                <url>' . $appUrl . '</url>
                            </applicationData>
                            <msgHeader>
                                <transactionID>' . $transactionId . '</transactionID>
                            </msgHeader>
                            <providerData>
                                <url>' . $url . '</url>
                            </providerData>'
        ;

        $soapXML = new \SoapVar($soapObjectXML, XSD_ANYXML, null, null, null, $schema);

        try {
            $result = $this->soapClient->RequestXcdrRegister($soapXML);
        } catch (\SoapFault $soapFault) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'message' => 'SoapFault encountered on Xcdr request.',
                'fault' => $soapFault
            );
        }

        $cdrFormat = array_key_exists('cdr_format', $options) ? $options['cdr_format'] : 'compact';

        if ($cdrFormat === 'detailed') {
            $cdrResult = $this->requestXcdrSetAttribute($result, $schema);
            if ($cdrResult['status'] === 'error') {
                return $cdrResult;
            }
        }

        return array(
            'status' => 'success',
            'message' => $host . ' registered successfully!',
            'result' => $result
        );
    }

    /**
     * Cisco IOS XCDRProvider Set Attribute Request
     */
    private function requestXcdrSetAttribute($result, $schema)
    {
        $msgHeader = $result['msgHeader'];

        $soapObjectXML = '<format>DETAIL</format>
                        <msgHeader>
				<transactionID>' . $msgHeader->transactionID . '</transactionID>
                                <registrationID>' . $msgHeader->registrationID . '</registrationID>
			</msgHeader>'
        ;

        $soapXML = new \SoapVar($soapObjectXML, XSD_ANYXML, null, null, null, $schema);

        try {
            $this->soapClient->RequestXcdrSetAttribute($soapXML);
        } catch (SoapFault $soapFault) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'message' => 'SoapFault encountered on Xcdr set cdr detail attribute.',
                'fault' => $soapFault
            );
        }

        return array(
            'status' => 'success',
            'message' => 'Detail cdr attribute set successfully.',
        );
    }

}
