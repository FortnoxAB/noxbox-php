<?php

/**
 * Connector against Nox Finans.
 * Handles all connection manipulation and http data.
 *
 * Requires: cURL
 *
 * @author  Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 *
 * @property mixed     _connection
 * @property string    _accesstoken     ERP Access Token
 *
 * @property string    response         Contains the response of a curl against noxbox.
 * @property string    error            Contains error messsage from either curl or http response code.
 * @property int       errorCode        Contains the error code (if any) that belongs to the error message.
 * @property int       httpCode         Contains the response http code from the request.
 */
class RESTConnector
{

	protected $_connection;
	protected $_accessToken;

	public $response;
	public $error;
	public $errorCode;
	public $httpCode;
	public $contentType;
	public $totalCount = 25;


	/**
	 * @method __construct
	 * Constructs a REST Connector with a given access token.
	 *
	 * @param string $accessToken ERP Access token
	 *
	 * @return \RESTConnector
	 */
	public function __construct($accessToken = null) {
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
		return $this;
	}


	/**
	 * @method connect
	 * Inits a curl connection to the remote URL.
	 *
	 * @param string $url Remote url
	 *
	 * @return resource
	 */
	public function connect($url) {
		$this->_connection = curl_init($url);

		return $this->_connection;
	}


	/**
	 * @method execute
	 * Executes current command against current connection handler.
	 *
	 * @throws RESTSystemException
	 * @return string
	 */
	public function execute() {

		$this->_validate();
		$this->_prepare();
		$this->_execute();
		$this->_getResponseInfo();

		if ($this->response === false) {
			throw new RESTSystemException(curl_error($this->_connection));
		}

		$this->_parseResponse();
		$this->_validateResponse();

		return $this->response;
	}


	/**
	 * @method close
	 * Closes the curl connection to the remote url.
	 *
	 * @return \RESTConnector
	 */
	public function close() {

		curl_close($this->_connection);

		$this->_connection = null;

		return $this;
	}


	/**
	 * @method setOption
	 * Adds a new option to the curl connection.
	 *
	 * @param string $name  Name of the option
	 * @param string $value Value of the option
	 *
	 * @return \RESTConnector
	 */
	public function setOption($name, $value) {
		curl_setopt($this->_connection, $name, $value);
		return $this;
	}


	/**
	 * @method _prepare
	 * Performs common preparation before executing a request.
	 *
	 * @return \RESTConnector
	 */
	protected function _prepare() {
		$this->setOption(CURLOPT_HTTPHEADER, array(
			'Content-type: application/json',
			'Accept: application/json',
			'Authentication-token: ' . $this->_accessToken,
			'Expect: '
		));
		$this->setOption(CURLOPT_RETURNTRANSFER, true);
		$this->setOption(CURLOPT_CONNECTTIMEOUT, 3);


		//Use when debug
		//$this->setOption(CURLINFO_HEADER_OUT, true);

		return $this;
	}


	/**
	 * @method _validateResponseHTTP
	 * Checks if response resulted in any internal curl error or/and HTTP error.
	 * http://confluence/display/NB/HTTP+Declaration
	 *
	 * @throws RESTSystemException
	 * @return \RESTConnector
	 */
	protected function _validateResponseHTTP() {
		$result  = $this->response ? $this->response : array();
		$code    = isset($result['code']) ? $result['code'] : null;
		$message = isset($result['message']) ? $result['message'] : null;

		//Request returned a success HTTP code.
		if (mb_substr($this->httpCode, 0, 1) != 4 && mb_substr($this->httpCode, 0, 1) != 5) {
			return $this;
		}

		//This should not be possible but if $result is false something went wrong.
		if ($result === false) {
			throw new RESTSystemException($message);
		}

		switch ($this->httpCode) {
			case 400: //Bad Request
				$this->_validateResponseHTTP_400($code, $message);
				break;
			case 401: //Missing/Invalid Authentication Token & Missing required rights.
				$this->_validateResponseHTTP_401($code, $message);
				break;
			case 403: //Missing required rights.
				$this->_validateResponseHTTP_403($code, $message);
				break;
			case 404: //Resource not found.
				$this->_validateResponseHTTP_404($code, $message);
				break;
			case 405: //Method Not Allowed
				throw new RESTSystemException($message);
				break;
			case 422: //Unprocessable Entity
				throw new RESTSystemException($message);
				break;
			case 500: //Internal Server Error
				throw new RESTSystemException($message);
				break;
			case 501: //Not Implemented
				throw new RESTSystemException($message);
				break;
			case 502: //Bad Gateway
				throw new RESTSystemException($message);
				break;
			case 503: //Service Unavailable
				throw new RESTSystemException($message);
				break;
			default:
				throw new RESTSystemException($message);
				break;
		}

		return $this;
	}


	protected function _validateResponseHTTP_400($code = null, $message = null) {
		switch ($code) {
			case 'BAD_REQUEST':
				throw new RESTClientException($message, $code);
				break;
			default:
				throw new RESTSystemException($message);
				break;
		}
	}


	protected function _validateResponseHTTP_401($code = null, $message = null) {
		switch ($code) {
			case 'MISSING_RIGHT':
				throw new RESTClientException($message, $code);
				break;
			case 'UNAUTHORIZED':
				throw new RESTSystemException($message);
				break;
			default:
				throw new RESTSystemException($message);
				break;
		}
	}


	protected function _validateResponseHTTP_403($code = null, $message = null) {
		switch ($code) {
			case 'MISSING_RIGHT':
				throw new RESTClientException($message, $code);
				break;
			default:
				throw new RESTSystemException($message);
				break;
		}
	}


	protected function _validateResponseHTTP_404($code = null, $message = null) {
		switch ($code) {
			case 'RESOURCE_NOT_FOUND':
				throw new RESTClientException($message, $code);
				break;
			default:
				throw new RESTSystemException($message);
				break;
		}
	}


	/**
	 * @internal param $result
	 *
	 * @return bool
	 */
	protected function _validateResponse() {

		$this->_validateResponseHTTP();

		return true;
	}


	/**
	 * Executes the CURL request.
	 *
	 * @return \RESTConnector
	 */
	protected function _execute() {
		$this->response = curl_exec($this->_connection);
		return $this;
	}


	/**
	 * Parses the response based on content type.
	 *
	 * @return \RESTConnector
	 */
	protected function _parseResponse() {

		if ($this->contentType === 'application/json') {
			$this->response = json_decode($this->response, true);
			$this->_extractPaginationInfo();
		}

		return $this;
	}


	/**
	 * Retrieves info from the curl response.
	 *
	 * @return \RESTConnector
	 */
	protected function _getResponseInfo() {
		$this->httpCode    = curl_getinfo($this->_connection, CURLINFO_HTTP_CODE);
		$this->contentType = curl_getinfo($this->_connection, CURLINFO_CONTENT_TYPE);
		return $this;
	}


	/**
	 * Validates the connection.
	 *
	 * @throws RESTSystemException
	 * @return \RESTConnector
	 */
	protected function _validate() {

		if (!$this->_accessToken) {
			throw new RESTSystemException('REST Error: Missing Authentication-token in headers.');
		}

		return $this;
	}


	/**
	 * Extracts pagination result.
	 *
	 * @internal param array $result
	 *
	 * @return string
	 */
	protected function _extractPaginationInfo() {
		if (!isset($this->response['count'])) {
			return true;
		}
		$this->totalCount = $this->response['count'];
		$this->response   = array_pop($this->response);
		return true;
	}


}