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

use Invite\Component\Cisco\Wsapi\Request\WsapiRequestInterface;

/**
 * INVITE WsApi Request Abstract class.
 *
 * @category   API
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
abstract class WsapiRequest implements WsapiRequestInterface
{

    protected $msgHeader;
    protected $applicationData;
    protected $providerData;
    protected $schema;
    protected $sequence;
    protected $interval;
    protected $failureCount;
    protected $registered = false;
    protected $providerStatus;
    protected $options = array();
    protected $valid = true;

    /**
     * Xcdr Soap Handler construct.
     * 
     * @param array additonal options
     */
    public function __construct(array$options = array())
    {
        $this->options = $options;
    }

    /**
     * Respond to wsapi keepalive probing response.
     */
    protected function probing($msgHeader, $sequence, $interval, $failureCount, $registered, $providerStatus)
    {
        $this->msgHeader = $msgHeader;
        $this->sequence = $sequence;
        $this->interval = $interval;
        $this->failureCount = $failureCount;
        $this->registered = $registered;
        $this->providerStatus = $providerStatus;

        $soapObjXML =
                '<msgHeader>
                    <registrationID>' . $this->getRegistrationId() . '</registrationID>
                    <transactionID>' . $this->getTransactionId() . '</transactionID>
                </msgHeader>
                <sequence>' . $this->getSequence() . '</sequence>'
        ;

        $soapXML = new \SoapVar($soapObjXML, XSD_ANYXML, null, null, null, $this->getSchema());

        return $soapXML;
    }

    /**
     * Respond to wsapi xcdr unregister response.
     */
    protected function unregister($msgHeader)
    {
        $this->msgHeader = $msgHeader;

        $soapObjXML =
                '<msgHeader>
                    <registrationID>' . $this->getRegistrationId() . '</registrationID>
                    <transactionID>' . $this->getTransactionId() . '</transactionID>
                </msgHeader>'
        ;

        $soapXML = new \SoapVar($soapObjXML, XSD_ANYXML, null, null, null, $this->getSchema());

        return $soapXML;
    }

    /**
     * Respond to wsapi xcdr status change message.
     */
    protected function status($msgHeader, $applicationData, $providerData, $providerStatus)
    {
        $this->msgHeader = $msgHeader;
        $this->applicationData = $applicationData;
        $this->providerData = $providerData;
        $this->providerStatus = $providerStatus;

        return;
    }

    /**
     * @return std Object
     */
    public function getMsgHeader()
    {
        return $this->msgHeader;
    }

    /**
     * @return string
     */
    public function getRegistrationId()
    {
        if (!$this->msgHeader) return;
        if (!isset($this->msgHeader->registrationID)) return;

        return $this->msgHeader->registrationID;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        if (!$this->msgHeader) return;
        if (!isset($this->msgHeader->transactionID)) return;

        return $this->msgHeader->transactionID;
    }

    /**
     * @return string
     */
    public function getApplicationData()
    {
        return $this->applicationData;
    }

    /**
     * @return string
     */
    public function getApplicationUrl()
    {
        if (!$this->applicationData) return;
        if (!isset($this->applicationData->url)) return;

        return $this->applicationData->url;
    }

    /**
     * @return string
     */
    public function getProviderData()
    {
        return $this->providerData;
    }

    /**
     * @return string
     */
    public function getProviderUrl()
    {
        if (!$this->providerData) return;
        if (!isset($this->providerData->url)) return;

        return $this->providerData->url;
    }

    /**
     * @return string
     */
    public function getProviderStatus()
    {
        return $this->providerStatus;
    }

    /**
     * @return string
     */
    public function setProviderStatus($status)
    {
        $this->providerStatus = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @return string
     */
    public function isInService()
    {
        return $this->providerStatus === 'IN_SERVICE' ? true : false;
    }

    /**
     * @return string
     */
    public function isShutdown()
    {
        return $this->providerStatus === 'SHUTDOWN' ? true : false;
    }

    /**
     * @return string
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return string
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @return string
     */
    public function getFailureCount()
    {
        return $this->failureCount;
    }

    /**
     * @return string
     */
    public function isRegistered()
    {
        return $this->registered;
    }

    /**
     * @return string
     */
    public function SetRegistered($registered)
    {
        $this->registered = $registered;
        return $this;
    }

    /**
     * @return string
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function setOptions(array$options)
    {
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function setOption($key, $option)
    {
        $this->options[$key] = $option;
    }

    /**
     * @return string
     */
    public function hasOption($option)
    {
        return array_key_exists($option, $this->options) ? true : false;
    }

}