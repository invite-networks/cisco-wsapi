<?php
/*
 * This file is part of the Invite Wsapi Bundle
 *
 * The bundle provides a mapping to Cisco's IOS UC Gateway Api.
 * 
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Component\Cisco\Wsapi\Handler;

use Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface;

/**
 * INVITE WsApi Xcdr Soap Handler.
 *
 * @category   API
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class XcdrHandler
{
    /**
     * Cisco Xcdr api xml schema
     */

    const XCDR_SCHEMA = 'http://www.cisco.com/schema/cisco_xcdr/v1_0/';

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
     * Respond to wsapi keepalive probing response.
     */
    public function SolicitXcdrProbing($msgHeader, $sequence, $interval, $failureCount, $registered, $providerStatus)
    {
        $data = array(
            'msgHeader' => $msgHeader,
            'sequence' => $sequence,
            'interval' => $interval,
            'failureCount' => $failureCount,
            'registered' => $registered,
            'providerStatus' => $providerStatus
        );

        $this->xcdrListener->processProbing($data);

        $schema = self::XCDR_SCHEMA;

        $soapObjXML =
                '<msgHeader>
                    <registrationID>' . $msgHeader->registrationID . '</registrationID>
                    <transactionID>' . $msgHeader->transactionID . '</transactionID>
                </msgHeader>
                <sequence>' . $sequence . '</sequence>'
        ;

        $soapXML = new \SoapVar($soapObjXML, XSD_ANYXML, null, null, null, $schema);

        return $soapXML;
    }

    /**
     * Respond to wsapi xcdr unregister response.
     */
    public function SolicitXcdrProviderUnRegister($msgHeader)
    {
        $data = array(
            'msgHeader' => $msgHeader
        );

        $response = $this->xcdrListener->processUnRegister($data);

        return $response;
    }

    /**
     * Respond to wsapi xcdr status change message.
     */
    public function NotifyXcdrStatus($msgHeader, $applicationData, $providerData, $providerStatus)
    {
        $data = array(
            'msgHeader' => $msgHeader,
            'applicationData' => $applicationData,
            'providerData' => $providerData,
            'providerStatus' => $providerStatus
        );

        $response = $this->xcdrListener->processStatus($data);

        return $response;
    }

    /**
     * Parse and save wsapi cdr record.
     */
    public function NotifyXcdrRecord($msgHeader, $format, $type, $cdr)
    {
        $data = array(
            'msgHeader' => $msgHeader,
            'format' => $format,
            'type' => $type,
            'cdr' => $cdr
        );

        $this->xcdrListener->processCdrRecord($data);
    }

}