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

use Invite\Component\Cisco\Wsapi\Exception\SoapTimeoutException;
use Invite\Component\Cisco\Wsapi\Client\WsApiClient;
use Invite\Component\Cisco\Wsapi\Request\XcdrRequest;

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
        $schema = XcdrRequest::XCDR_SCHEMA;
        $appName = array_key_exists('appName', $options) ? $options['appName'] : 'invite_xcdr';
        $transactionID = array_key_exists('transactionID', $options) ? $options['transactionID'] : uniqid('xcdr');

        $socket = array_key_exists('socket', $options) ? $options['socket'] : 25;
        ini_set('default_socket_timeout', $socket);

        $connection = array_key_exists('connection', $options) ? $options['connection'] : 25;
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
                                <transactionID>' . $transactionID . '</transactionID>
                            </msgHeader>
                            <providerData>
                                <url>' . $url . '</url>
                            </providerData>'
        ;

        $soapXML = new \SoapVar($soapObjectXML, XSD_ANYXML, null, null, null, $schema);

        try {
            $result = $this->soapClient->RequestXcdrRegister($soapXML);
        } catch (SoapTimeoutException $e) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'message' => 'XCDR Soap Client Timeout. ' . $e->getMessage(),
                'class' => get_class($this)
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'type' => 'exception',
                'message' => 'XCDR Soap Client Exception. ' . $e->getMessage(),
                'class' => get_class($this)
            );
        }

        if (is_soap_fault($result)) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'code' => $result->faultcode,
                'message' => $result->faultstring,
                'class' => get_class($this)
            );
        }

        $cdrFormat = array_key_exists('cdr_format', $options) ? $options['cdr_format'] : 'compact';

        if ($cdrFormat === 'detailed') {
            $cdrResult = $this->requestXcdrSetAttribute($result, $schema);
            if ($cdrResult['status'] === 'error') {
                return $cdrResult;
            }
        }

        $result['app.name'] = $appName;
        $result['app.url'] = $appUrl;
        $result['schema'] = $schema;
        $result['provider.url'] = $url;

        return array(
            'status' => 'success',
            'message' => $host . ' registered successfully!',
            'result' => $result
        );
    }

    /**
     * Cisco IOS XCDR Provider Registration
     */
    public function requestXcdrUnRegister($host, $registrationID, $transactionID, array$options = array())
    {
        $protocol = array_key_exists('protocol', $options) ? $options['protocol'] : 'http';
        $url = $protocol . '://' . $host . ':8090/cisco_xcdr';
        $schema = XcdrHandler::XCDR_SCHEMA;

        $socket = array_key_exists('socket', $options) ? $options['socket'] : 25;
        ini_set('default_socket_timeout', $socket);

        $connection = array_key_exists('connection', $options) ? $options['connection'] : 25;
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

        $soapObjectXML = '<msgHeader>
                            <transactionID>' . $transactionID . '</transactionID>
                            <registrationID>' . $registrationID . '</registrationID>
                          </msgHeader>'
        ;

        $soapXML = new \SoapVar($soapObjectXML, XSD_ANYXML, null, null, null, $schema);

        try {
            $result = $this->soapClient->RequestXcdrUnRegister($soapXML);
        } catch (SoapTimeoutException $e) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'message' => 'XCDR Soap Client Timeout. ' . $e->getMessage(),
                'class' => get_class($this)
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'type' => 'exception',
                'message' => 'XCDR Soap Client Exception. ' . $e->getMessage(),
                'class' => get_class($this)
            );
        }

        if (is_soap_fault($result)) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'code' => $result->faultcode,
                'message' => $result->faultstring,
                'class' => get_class($this)
            );
        }

        return array(
            'status' => 'success',
            'message' => $host . ' unregistered successfully!',
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
            $result = $this->soapClient->RequestXcdrSetAttribute($soapXML);
        } catch (SoapTimeoutException $e) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'message' => 'XCDR Soap Client Timeout. ' . $e->getMessage(),
                'class' => get_class($this)
            );
        } catch (\Exception $e) {
            return array(
                'status' => 'error',
                'type' => 'exception',
                'message' => 'XCDR Soap Client Exception. ' . $e->getMessage(),
                'class' => get_class($this)
            );
        }

        if (is_soap_fault($result)) {
            return array(
                'status' => 'error',
                'type' => 'soap_fault',
                'code' => $result->faultcode,
                'message' => $result->faultstring,
                'class' => get_class($this)
            );
        }

        return array(
            'status' => 'success',
            'result' => $result
        );
    }

}
