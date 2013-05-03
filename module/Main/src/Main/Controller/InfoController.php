<?php

namespace Main\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

/**
 *
 */
class InfoController extends AbstractRestfulController
{
	/**
	 * Return list of resources
	 *
	 * @return array
	 */
	public function getList()
	{
		// call: http://ehcserver.localhost/main/info.json
		$data = array(
			'phone'   => '+30123456789',
			'email'   => 'email@domain',
		);

		return $data;
	}

	/**
	 * Return single resource
	 *
	 * @param mixed $id
	 * @return mixed
	 */
	public function get($id) {
		// call: http://ehcserver.localhost/info.json/123
		$data = array(
			'phone'   => '1111',
			'email'   => 'email@domain',
		);
		return $data;
	}

	/**
	 * Create a new resource
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function create($data) {}

	/**
	 * Update an existing resource
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return mixed
	 */
	public function update($id, $data) {}

	/**
	 * Delete an existing resource
	 *
	 * @param  mixed $id
	 * @return mixed
	 */
	public function delete($id) {}
}
