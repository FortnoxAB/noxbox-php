<?php

namespace NoxBox;

use RESTClient;

/**
 * Abstract class for REST resources.
 * All resources inherit from this one which contains base methods and properties.
 *
 * @author  Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 *
 * @property RESTClient      _client        Holds the REST client for nox box.
 * @property string          _endpoint      Holds the endpoint for the resource resource.
 */
abstract class Resource
{

	protected $_client;
	protected $_endpoint;
  protected $_accessToken;


  /**
	 * @method __construct
	 * Constructs a REST Resource.
	 *
	 * @param string $accessToken ERP access token
	 * @param string $mode        Test/Production
	 *
	 * @return \NoxBox\Resource
	 */
	public function __construct($accessToken = null, $mode = 'test') {

		//Start the REST client.
    $this->_accessToken = $accessToken;

    return $this;
	}


	/**
	 * @param $accessToken
	 *
	 * @return $this
	 */
	public function setAccessToken($accessToken) {
    $this->_accessToken = $accessToken;
    $this->_client->setAccessToken($accessToken);
		return $this;
	}


  /**
   * @param $mode
   *
   * @return $this
   */
  public function init($mode = 'test') {
    if ($mode === 'production') {
      require_once('config/production.php');
    } else if ($mode === 'development') {
      require_once('config/development.php');
    } else {
      require_once('config/test.php');
    }

    $this->_client = new RESTClient(SERVER_URL, SERVER_PORT, SERVER_PROTOCOL, $this->_accessToken);

    return $this;
  }


  /**
	 *
	 * @return $this
	 */
	public function getTotalCount() {
		return $this->_client->getTotalCount();
	}


	/**
	 * @method create
	 * Performs a POST (Create) request against defined endpoint.
	 *
	 * @param null  $id
	 * @param array $data Array with post data.
	 *
	 * @return array $result
	 */
	public function create($id = null, array $data) {

		if ($id) {
			$endpoint = $this->_endpoint . '/' . $id;
		} else {
			$endpoint = $this->_endpoint;
		}

		$result = $this->_client->post($endpoint, $data);

		return $result;
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

		if ($id) {
			$endpoint = $this->_endpoint . '/' . $id;
		} else {
			$endpoint = $this->_endpoint;
		}

		$result = $this->_client->put($endpoint, $data);

		return $result;
	}


	/**
	 * @method find
	 * Performs a GET (find) request against defined endpoint.
	 *
	 * @param string $id If id is given. Search for that record - otherwise find all.
	 * @param array  $searchParameters
	 * @param array  $paginationInfo
	 *
	 * @return array $result
	 */
	public function find($id = null, array $searchParameters = array(), array $paginationInfo = array()) {

		$result = $this->_client->get($this->_endpoint, $id, $searchParameters, $paginationInfo);

		return $result;
	}


	/**
	 * @method delete
	 * Performs a DELETE (delete) request against defined endpoint.
	 *
	 * @param string $id Id of the record that should be deleted.
	 *
	 * @return array $result
	 */
	public function delete($id) {

		$result = $this->_client->delete($this->_endpoint, $id);

		return $result;
	}


}