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
namespace Invite\Component\Cisco\Wsapi\Request;

use Invite\Component\Cisco\Wsapi\Request\WsapiRequest;
use Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface;

/**
 * INVITE WsApi Xcdr Request.
 *
 * @category   API
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class XcdrRequest extends WsapiRequest
{
    /**
     * Cisco Xcdr api xml schema
     */

    const XCDR_SCHEMA = 'http://www.cisco.com/schema/cisco_xcdr/v1_0';

    /**
     * @var \Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface
     */
    protected $listener;
    protected $cdrFormat;
    protected $cdrType;
    protected $cdrRecord;

    /**
     * Xcdr Soap Handler construct.
     * 
     * @param \Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface $listener
     */
    public function __construct(XcdrListenerInterface $listener, array$options = array())
    {
        parent::__construct($options);
        $this->listener = $listener;
        $this->schema = self::XCDR_SCHEMA;
    }

    /**
     * Respond to wsapi keepalive probing response.
     */
    public function SolicitXcdrProbing($msgHeader, $sequence, $interval, $failureCount, $registered, $providerStatus)
    {
        $responseXml = $this->probing($msgHeader, $sequence, $interval, $failureCount, $registered, $providerStatus);
        $response = $this->listener->processProbing($this, $responseXml);

        if (!$this->isValid()) return;
        return $response;
    }

    /**
     * Respond to wsapi xcdr unregister response.
     */
    public function SolicitXcdrProviderUnRegister($msgHeader)
    {
        $responseXml = $this->unregister($msgHeader);
        $response = $this->listener->processUnregister($this, $responseXml);

        if (!$this->isValid()) return;
        return $response;
    }

    /**
     * Respond to wsapi xcdr status change message.
     */
    public function NotifyXcdrStatus($msgHeader, $applicationData, $providerData, $providerStatus)
    {
        $responseXml = $this->status($msgHeader, $applicationData, $providerData, $providerStatus);
        $response = $this->listener->processStatus($this, $responseXml);

        if (!$this->isValid()) return;
        return $response;
    }

    /**
     * Parse and save wsapi cdr record.
     */
    public function NotifyXcdrRecord($msgHeader, $format, $type, $cdr)
    {
        $this->msgHeader = $msgHeader;
        $this->registrationID = $msgHeader->registrationID;
        $this->transactionID = $msgHeader->transactionID;
        $this->cdrFormat = $format;
        $this->cdrType = $type;
        $this->cdrRecord = $cdr;

        $response = $this->listener->processRecord($this, null);

        if (!$this->isValid()) return;
        return $response;
    }

    /**
     * @return std Object
     */
    public function getCdrFormat()
    {
        return $this->cdrFormat;
    }

    /**
     * @return string
     */
    public function getCdrType()
    {
        return $this->cdrType;
    }

    /**
     * @return string
     */
    public function getCdrRecord()
    {
        return $this->cdrRecord;
    }

}