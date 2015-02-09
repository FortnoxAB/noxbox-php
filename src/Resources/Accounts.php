<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Accounts resource
 *
 * @author	Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 */

class Accounts extends Resource {

	/**
	 * Constructs a resource and assigns the endpoint.
	 *
	 * @param        $accessToken
	 * @param string $mode
	 *
	 * @returns Accounts
	 */
	public function __construct($accessToken, $mode = 'production') {
		$this->_endpoint = 'accounts';
		return parent::__construct($accessToken, $mode);;
	}


}