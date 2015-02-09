<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Clients resource
 *
 * @author  Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 */
class Clients extends Resource
{

	/**
	 * Constructs a resource and assigns the endpoint.
	 *
	 * @param string $accessToken
	 * @param string $mode
	 *
	 * @return \NoxBox\Resources\Clients
	 */
	public function __construct($accessToken = null, $mode = 'production') {
		$this->_endpoint = 'my';
		return parent::__construct($accessToken, $mode);;
	}


	/**
	 * Registers external access token for the client.
	 *
	 * @param $externalAccessToken
	 * @param $erpName
	 *
	 * @return array
	 */
	public function setExternalAccessToken($externalAccessToken, $erpName) {
		$this->_endpoint = 'my/accesstoken';
		return parent::create(null, array(
			'externalSystemAccessToken' => $externalAccessToken,
			'noxIntegration'            => $erpName,
			'status'                    => 'Active'
		));
	}


	/**
	 * @method find
	 * Performs a GET (Find) request against defined endpoint.
	 *
	 * @param null  $id
	 *
	 * @param array $searchParameters
	 * @param array $paginationInfo
	 *
	 * @return array $result
	 */
	public function find($id = null, array $searchParameters = array(), array $paginationInfo = array()) {
		$this->_endpoint = 'clients';
		return parent::find($id, $searchParameters, $paginationInfo);
	}

	/**
	 * @method update
	 * Performs a POST (Create) request against defined endpoint.
	 *
	 * @param null  $id
	 * @param array $data Array with put data.
	 *
	 * @return array $result
	 */
	public function create($id = null, array $data) {
		$this->_endpoint = 'clients';
		return parent::create($id, $data);
	}


	/**
	 * @method update
	 * Performs a PUT (Update) request against defined endpoint.
	 *
	 * @param null  $id
	 * @param array $data Array with put data.
	 *
	 * @return array $result
	 */
	public function update($id = null, array $data) {
		unset($data['authenticationToken']);
		return parent::update(null, $data);
	}

}