<?php

namespace NoxBox\Resources;

use NoxBox\Resource;

/**
 * Invoices resource
 *
 * @author  Jakob Carlbring Alm (jakob.c.alm@fortnox.se)
 */
class Invoices extends Resource
{

	/**
	 * Constructs a resource and assigns the endpoint.
	 *
	 * @param        $accessToken
	 * @param string $mode
	 *
	 * @returns Invoices
	 */
	public function __construct($accessToken = null, $mode = 'production') {
		$this->_endpoint = 'invoices';
		return parent::__construct($accessToken, $mode);;
	}


	/**
	 * @method findPlannedEvents
	 * Returns all planned events for invoice with given id.
	 *
	 * @param string $id
	 *
	 * @return array $result
	 */
	public function findPlannedEvents($id = null) {
		return $this->find($id . '/events/planned');
	}


	/**
	 * @method findPerformedEvents
	 * Returns all performed events for invoice with given id.
	 *
	 * @param string $id
	 *
	 * @return array $result
	 */
	public function findPerformedEvents($id = null) {
		return $this->find($id . '/events/performed');
	}


	/**
	 * @method findPlannedEvents
	 * Returns all planned events for invoice with given id.
	 *
	 * @param string $id
	 *
	 * @return array $result
	 */
	public function findFinancialTransactions($id = null) {
		return $this->find($id . '/transactions');
	}


	/**
	 * @method stop
	 * Stops invoice in Nox Finans
	 *
	 * @param string $id
	 *
	 * @return array $result
	 */
	public function stop($id = null) {
		return $this->update($id . '/write-off', array());
	}


	/**
	 * @method pause
	 * Pauses invoice in Nox Finans
	 *
	 * @param string $id
	 * @param        $toDate
	 *
	 * @return array $result
	 */
	public function pause($id = null, $toDate) {
		return $this->update($id . '/pause', array('date' => $toDate));
	}


	/**
	 * @method pause
	 * Adds payment to invoice in Nox Finans
	 *
	 * @param string $id
	 * @param        $amount
	 *
	 * @internal param $toDate
	 *
	 * @return array $result
	 */
	public function payment($id = null, $amount) {
		return $this->update($id . '/payment', array('amount' => $amount));
	}

}