<?php namespace Wistia\api;

use Wistia\Wistia;

class Medias
{

	public function __construct(Wistia $client)
	{
		$this->client = $client;
	}

	/**
	 * Lists all medias
	 * @param  array $filters Array of parameters and filters for the list
	 * @return array
	 */
	public function index($filters = array())
	{
		return $this->client->get('medias', $filters);
	}
}