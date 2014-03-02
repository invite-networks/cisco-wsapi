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
     * @array Xcdr client options
     */
    protected $options = array();

    /**
     * @var \Invite\Component\Cisco\Wsapi\Soap\Client\WsApiSoapClient
     */
    protected $soapClient;

    /**
     * Xcdr Soap client construct.
     * 
     * @param array of client setup parameters $options
     */
    public function __construct(array$options)
    {
        $this->options = $options;
    }

    /**
     * Cisco IOS XCDR Provider Registration
     */
    public function requestXcdrRegister($host, $appUrl)
    {
        $protocol = array_key_exists('protocol', $this->options) ? $this->options['protocol'] : 'http';
        $url = $protocol . '://' . $host . ':8090/cisco_xcdr';
        $schema = XcdrHandler::XCDR_SCHEMA;
        $appName = $this->options['app_name'];
        $uniqueId = uniqid('xcdr');

        $socket = array_key_exists('socket', $this->options) ? $this->options['socket'] : 5;
        ini_set('default_socket_timeout', $socket);

        $connection = array_key_exists('connection', $this->options) ? $this->options['connection'] : 5;
        $trace = array_key_exists('trace', $this->options) ? $this->options['trace'] : false;
        $exception = array_key_exists('exception', $this->options) ? $this->options['exception'] : false;

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
                                <transactionID>' . $uniqueId . '</transactionID>
                            </msgHeader>
                            <providerData>
                                <url>' . $url . '</url>
                            </providerData>'
        ;

        $soapXML = new \SoapVar($soapObjectXML, XSD_ANYXML, null, null, null, $schema);

        try {
            $result = $this->soapClient->RequestXcdrRegister($soapXML);
        } catch (\SoapFault $soapFault) {
            // TODO figure out what to do here.
            return $soapFault;
        }

        $cdrFormat = array_key_exists('cdr_format', $this->options) ? $this->options['cdr_format'] : 'compact';

        if ($cdrFormat === 'detailed') {
            $this->requestXcdrSetAttribute($result, $schema);
        }

        return $result;
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
            // TODO figure out what to do here.
            return $soapFault;
        }

        return;
    }

}
