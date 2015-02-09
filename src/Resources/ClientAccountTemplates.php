<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Client account templates resource
 *
 * @author	Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 */

class ClientAccountTemplates extends Resource {

	/**
	 * Constructs a resource and assigns the endpoint.
	 *
	 * @param string $accessToken
	 * @param string $mode
	 *
	 * @return \NoxBox\Resources\ClientAccountTemplates
	 */
	public function __construct($accessToken = null, $mode = 'production') {
		$this->_endpoint = 'account-settings';
		return parent::__construct($accessToken, $mode);;
	}


	/**
	 * @param null|string $id
	 *
	 * @return array
	 */
	public function findTemplate($id = null) {
		$this->_endpoint = 'accounts/templates';
		$result = parent::find($id);
		$this->_endpoint = 'account-settings';
		return $result;
	}


}