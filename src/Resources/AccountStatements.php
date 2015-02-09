<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Account statement resource
 *
 * @author	Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 */

class AccountStatements extends Resource {

	/**
	 * Constructs a resource and assigns the endpoint.
	 *
	 * @param null|string $accessToken
	 * @param string      $mode
	 *
	 * @return \NoxBox\Resources\AccountStatements
	 */
	public function __construct($accessToken = null, $mode = 'production') {
		$this->_endpoint = 'accounts';
		return parent::__construct($accessToken, $mode);;
	}


	/**
	 * @method find
	 * Performs a GET (find) request against defined endpoint.
	 *
	 * @param string $id If id is given. Search for that record - otherwise find all.
	 * @param array  $searchParameters
	 *
	 * @param array  $paginationInfo
	 *
	 * @return array $result
	 */
	public function find($id = null, array $searchParameters = array(), array $paginationInfo = array()) {
		$accountId = $searchParameters['accountNo'];
		unset($searchParameters['accountNo']);
		return parent::find($accountId . '/account-statements', $searchParameters, $paginationInfo);
	}


}