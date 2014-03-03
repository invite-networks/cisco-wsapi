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
    protected $registrationID;
    protected $transactionID;
    protected $applicationData;
    protected $applicationUrl;
    protected $providerData;
    protected $providerUrl;
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
        $this->registrationID = $msgHeader->registrationID;
        $this->transactionID = $msgHeader->transactionID;
        $this->sequence = $sequence;
        $this->interval = $interval;
        $this->failureCount = $failureCount;
        $this->registered = $registered;
        $this->providerStatus = $providerStatus;

        $soapObjXML =
                '<msgHeader>
                    <registrationID>' . $this->registrationID . '</registrationID>
                    <transactionID>' . $this->transactionID . '</transactionID>
                </msgHeader>
                <sequence>' . $this->sequence . '</sequence>'
        ;

        $soapXML = new \SoapVar($soapObjXML, XSD_ANYXML, null, null, null, $this->schema);

        return $soapXML;
    }

    /**
     * Respond to wsapi xcdr unregister response.
     */
    protected function unregister($msgHeader)
    {
        $this->msgHeader = $msgHeader;
        $this->registrationID = $msgHeader->registrationID;
        $this->transactionID = $msgHeader->transactionID;

        $soapObjXML =
                '<msgHeader>
                    <registrationID>' . $this->registrationID . '</registrationID>
                    <transactionID>' . $this->transactionID . '</transactionID>
                </msgHeader>'
        ;

        $soapXML = new \SoapVar($soapObjXML, XSD_ANYXML, null, null, null, $this->schema);

        return $soapXML;
    }

    /**
     * Respond to wsapi xcdr status change message.
     */
    protected function status($msgHeader, $applicationData, $providerData, $providerStatus)
    {
        $this->msgHeader = $msgHeader;
        $this->transactionID = $msgHeader->transactionID;
        $this->applicationData = $applicationData;
        $this->applicationUrl = $applicationData->url;
        $this->providerData = $providerData;
        $this->providerUrl = $providerData->url;
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
    public function getregistrationID()
    {
        return $this->registrationID;
    }

    /**
     * @return string
     */
    public function gettransactionID()
    {
        return $this->transactionID;
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
        return $this->applicationUrl;
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
        return $this->providerUrl;
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
    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
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