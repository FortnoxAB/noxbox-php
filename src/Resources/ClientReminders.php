<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Client reminder settings resource
 *
 * @author  Andreas Cederström (andreas.cederstrom@fortnox.se)
 */

class ClientReminders extends Resource {

  /**
   * Constructs a resource and assigns the endpoint.
   *
   * @param string $accessToken
   * @param string $mode
   *
   * @return \NoxBox\Resources\ClientReminders
   */
  public function __construct($accessToken = null, $mode = 'production') {
    $this->_endpoint = 'my/notification-settings';
    return parent::__construct($accessToken, $mode);;
  }

}