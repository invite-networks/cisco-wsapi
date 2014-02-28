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
    public function processProbing($data);

    /**
     * Implementing class must provide method to
     * process status update.
     * 
     * @param array $data
     */
    public function processStatus($data);

    /**
     * Implementing class must provide method to
     * process unregister msg.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processUnRegister($data);

    /**
     * Implementing class must provide method to
     * set csv data in class.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processCdrRecord($data);
}