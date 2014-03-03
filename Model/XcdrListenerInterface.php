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
namespace Invite\Component\Cisco\Wsapi\Model;

use Invite\Component\Cisco\Wsapi\Request\WsapiRequestInterface;
use Invite\Component\Cisco\Wsapi\Request\XcdrRequest;

/**
 * INVITE WsApi Xcdr Cdr Interface.
 *
 * Must be implemented to rec csv data from Xcdr api.
 * 
 * @category   API
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
interface XcdrListenerInterface
{

    /**
     * Implementing class must provide method to
     * process probing updates.
     * 
     * @param array $data
     */
    public function processProbing(WsapiRequestInterface $probingRequest, $responseXml);

    /**
     * Implementing class must provide method to
     * process status update.
     * 
     * @param array $data
     */
    public function processStatus(WsapiRequestInterface $statusRequest, $responseXml = null);

    /**
     * Implementing class must provide method to
     * process unregister msg.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processUnregister(WsapiRequestInterface $unregisterRequest, $responseXml);

    /**
     * Implementing class must provide method to
     * set csv data in class.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processRecord(XcdrRequest $recordRequest, $responseXml = null);
}