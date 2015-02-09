<?php

/**
 * REST Client for integrations against Nox Box
 * Mimics the POST/PUT/GET/DELETE actions.
 *
 * @author  Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 *
 * @property mixed           _baseURI        Base uri to nox box server
 * @property RESTConnector   _connector      Holds the connection to Nox Box.
 */
class RESTClient
{

	protected $_baseURI;
	protected $_connector;
	protected $_resultCount;


	/**
	 * @method __construct
	 * Constructs a REST Client with given server information.
	 *
	 * @param string $url         server url
	 * @param int    $port        server port
	 * @param string $protocol    server protocol (http/https)
	 * @param string $accessToken ERP access token
	 *
	 * @return \RESTClient
	 */
	public function __construct($url, $port, $protocol = 'http', $accessToken = null) {

		$this->_baseURI   = $protocol . '://' . $url . ':' . $port . '/';
		$this->_connector = new RESTConnector($accessToken);

		return $this;
	}


	/**
	 * @param $accessToken
	 *
	 * @return $this
	 */
	public function setAccessToken($accessToken) {
		$this->_connector->setAccessToken($accessToken);
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getBaseURI() {
		return $this->_baseURI;
	}


	/**
	 * @method get
	 * Performs a GET request against given resource.
	 * Retrieves a single entity if id is given.
	 * Retrieves all entities if id is not given.
	 *
	 * @param string $resource
	 * @param string $id
	 * @param array  $searchParameters
	 * @param array  $paginationInfo
	 *
	 * @return mixed  $response
	 */
	public function get($resource, $id = null, array $searchParameters = array(), $paginationInfo = array()) {

		$searchQuery     = $this->_extractSearchQuery($searchParameters);
		$paginationQuery = $this->_extractPaginationQuery($paginationInfo, $searchQuery);

		if ($id) {
			$this->_connector->connect($this->_baseURI . $resource . '/' . $id . $searchQuery . $paginationQuery);
		} else {
			$this->_connector->connect($this->_baseURI . $resource . $searchQuery . $paginationQuery);
		}

		$this->_setConnectorOptions('get');

		$response = $this->_connector->execute();

		$this->_connector->close();

		return $response;
	}


	/**
	 * @method post
	 * Performs a POST request against given resource with given data
	 *
	 * @param string $resource
	 * @param array  $data
	 *
	 * @return mixed  $response
	 */
	public function post($resource, $data) {

		$this->_connector->connect($this->_baseURI . $resource);

		$this->_setConnectorOptions('post', $data);

		$response = $this->_connector->execute();

		$this->_connector->close();

		return $response;
	}


	/**
	 * @method post
	 * Performs a PUT request against given resource with given data
	 *
	 * @param string $resource
	 * @param array  $data
	 *
	 * @return mixed  $response
	 */
	public function put($resource, $data) {

		$this->_connector->connect($this->_baseURI . $resource);

		$this->_setConnectorOptions('put', $data);

		$response = $this->_connector->execute();

		$this->_connector->close();

		return $response;

	}


	/**
	 * @method delete
	 * Performs a DELETE request against given resource with given id
	 *
	 * @param string $resource
	 * @param string $id
	 *
	 * @return mixed  $response
	 */
	public function delete($resource, $id) {

		$this->_connector->connect($this->_baseURI . $resource . '/' . $id);

		$this->_setConnectorOptions('delete');

		$response = $this->_connector->execute();

		$this->_connector->close();

		return $response;
	}


	/**
	 * @method getErrorMessage
	 * Returns current error message from connector.
	 *
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->_connector->error;
	}


	/**
	 * @method getErrorCode
	 * Returns current error message from connector.
	 *
	 * @return int
	 */
	public function getErrorCode() {
		return $this->_connector->errorCode;
	}


	/**
	 * @return mixed
	 */
	public function getTotalCount() {
		return $this->_connector->totalCount;
	}

	/**
	 * @method _setConnectorOptions
	 * Sets standard curl options depending on method.
	 *
	 * @param string $method get/post/put/delete
	 * @param array  $data   payload data
	 *
	 * @return RESTClient
	 */
	protected function _setConnectorOptions($method, $data = array()) {

		$data = json_encode($data);

		switch ($method) {
			case 'get':
				$this->_connector->setOption(CURLOPT_HTTPGET, true);
				break;
			case 'post':
				$this->_connector->setOption(CURLOPT_POST, true);
				$this->_connector->setOption(CURLOPT_POSTFIELDS, $data);
				$this->_connector->setOption(CURLOPT_HTTPHEADER, array(
					'Content-Length: ' . strlen($data)
				));
				break;
			case 'put':
				$this->_connector->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
				$this->_connector->setOption(CURLOPT_POSTFIELDS, $data);
				$this->_connector->setOption(CURLOPT_HTTPHEADER, array(
					'Content-Length: ' . strlen($data)
				));
				break;
			case 'delete':
				$this->_connector->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}

		return $this;
	}


	/**
	 * Extracts search query from given parameters.
	 *
	 * @param array $searchParameters
	 *
	 * @return string
	 */
	protected function _extractSearchQuery(array $searchParameters = array()) {
		$query = '';

		if (!$searchParameters) {
			return $query;
		}

		$query .= '?';

		foreach ($searchParameters as $searchParameterName => $searchParameterValue) {
			if ($searchParameterValue === null) {
				continue;
			}

			$searchParameterValues = is_array($searchParameterValue) ? $searchParameterValue : array($searchParameterValue);

			foreach ($searchParameterValues as $parameterValue) {
				$query .= $searchParameterName . '=' . $parameterValue . '&';
			}
		}

		$query = mb_substr($query, 0, mb_strlen($query) - 1);

		return $query;
	}


	/**
	 * Extracts pagination info from given parameters.
	 *
	 * @param array $paginationInfo
	 *
	 * @param       $searchQuery
	 *
	 * @return string
	 */
	protected function _extractPaginationQuery(array $paginationInfo = array(), $searchQuery) {
		$query = '';

		if (!$paginationInfo) {
			return $query;
		}

		foreach ($paginationInfo as $paginationInfoName => $paginationValue) {
			if ($paginationValue === null) {
				continue;
			}
			$query .=  '&' . $paginationInfoName . '=' . $paginationValue;
		}

		if ( !$searchQuery ) {
			$query = mb_substr($query, 1);
			$query = '?' . $query;
		}

		return $query;
	}


}