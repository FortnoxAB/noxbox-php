<?php

/**
 * Class RESTException
 */
abstract class RESTException extends Exception
{

	protected $_httpMessage;


	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Construct the exception. Note: The message is NOT binary safe.
	 *
	 * @link http://php.net/manual/en/exception.construct.php
	 *
	 * @param string    $message  [optional] The Exception message to throw.
	 * @param null      $RESTCode
	 * @param int       $code     [optional] The Exception code.
	 * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
	 */
	public function __construct($message = null, $RESTCode = null, $code = 0, Exception $previous = null) {
		parent::__construct($this->_getMessage($RESTCode, $message), $code, $previous);
	}


	protected function _getMessage($RESTCode, $message) {
		$message = $message ? $message : $this->_httpMessage;

		if (is_array($message)) {
			$message = implode(', ', $message);
		}

		if ($RESTCode) {
			$message = $RESTCode . ':' . $message;
		}

		return $message;
	}

}


class RESTClientException extends RESTException
{

	protected $_httpMessage = 'Something was wrong with the request to Nox Finans. Please contact your support for further investigation.';


}

class RESTSystemException extends RESTException
{

	protected $_httpMessage = 'There was a server error during a request to Nox Finans. This should be investigated and fixed as soon as possible.';


}
