<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Deocumtnts resource
 *
 * @author	Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 */

class Documents extends Resource {

	/**
	 * Constructs a resource and assigns the endpoint.
	 *
	 * @param        $accessToken
	 * @param string $mode
	 *
	 * @returns Documents
	 */
	public function __construct($accessToken = null, $mode = 'production') {
		$this->_endpoint = 'documents';
		return parent::__construct($accessToken, $mode);;
	}


	/**
	 * Retrieves print link for pdf.
	 *
	 * @param $id
	 *
	 * @return string
	 */
	public function findPrintLink($id) {
		return $this->_client->getBaseURI() . $this->_endpoint . '/' . $id;
	}

}