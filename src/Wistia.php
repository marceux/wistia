<?php namespace Wistia;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Wistia
{
	protected $pass;
	protected $format = 'json';

	private $client;
	private $apis = array();

	/**
	 * Construct Wistia API object using the acct number and
	 * authentication key
	 * @param string $acct Account number
	 * @param string $auth Authentication key
	 * @param string $format The format the responses and requests will be in
	 */
	public function __construct($pass, $format = 'json')
	{
		// Set base properties
		$this->pass = $pass;
		$this->format = $format;
		
		// Create HTTP Client with some default options
		$this->client = new Client([
			'base_url' => "https://api.wistia.com/v1/",
			'defaults' => [
				'auth' => ['api', $pass]
			]
		]);
	}

	/**
	 * Appends the format of the request to the target uri
	 * @param  string $target The target URI
	 * @return string         Target URI with appended format (.json or .xml)
	 */
	private function buildTarget($target)
	{
		return $target . '.' . $this->format;
	}

	/**
	 * Function to handle exceptions caught in the request methods
	 * @param  object $exception [description]
	 * @return [type]            [description]
	 */
	private function handleException($exception)
	{			
		// Check if the Exception has a response
		if ($exception->hasResponse())
		{
			// Use the exception response to a build an array to return
			// with the status code (so at least we have something)
			$response = $exception->getResponse();
			$status = $response->getStatusCode();
			return ['status' => $status];
		}
		else
			return ['status' => 404];
	}

	/**
	 * Function to perform GET method on $target URI with $params as query parameters
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Wistia returns as an array structure
	 */
	public function get($target, $params = [])
	{
		$target = $this->buildTarget($target);

		// Try to get a response from Wistia API using Guzzle Client
		try
		{
			$response = $this->client->get($target, ['query' => $params]);

			// If successful, return an array that decodes the returned JSON
			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Function to perform POST method on $target URI with $params as JSON body
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Wistia returns as an array structure
	 */
	public function post($target, $params = [])
	{
		$target = $this->buildTarget($target);

		try
		{
			$response = $this->client->post($target, [
				'json' => $params,
				'headers' => [
					'Accept' => 'application/json'
				]
			]);

			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Function to perform PUT method on $target URI with $params as JSON body
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Wistia returns as an array structure
	 */
	public function put($target, $params = [])
	{
		$target = $this->buildTarget($target);

		try
		{
			$response = $this->client->put($target, [
				'json' => $params,
				'headers' => [
					'Accept' => 'application/json'
				]
			]);

			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Function to perform DELETE method on $target URI with $params as query parameters
	 * @param  string $target The URI target that will be added to the base url
	 * @param  array  $params The query string and values structured as an array
	 * @return array          What Wistia returns as an array structure
	 */
	public function delete($target, $params = [])
	{
		$target = $this->buildTarget($target);

		try
		{
			$response = $this->client->delete($target, ['query' => $params]);
			return json_decode($response->getBody(), true);
		}
		catch (ClientException $e)
		{
			return $this->handleException($e);
		}
	}

	/**
	 * Returns the requested class name, optionally using a cached array so no
	 * object is instantiated more than once during a request.
	 *
	 * @param string $class
	 * @return mixed
	 */
	public function getApi($class)
	{
		$class = '\Wistia\api\\' . $class;

		if (!array_key_exists($class, $this->apis))
		{
			$this->apis[$class] = new $class($this);
		}

		return $this->apis[$class];
	}

	/**
	 * @return  \Marceux\Wistia\api\Medias
	 */
	public function medias()
	{
		return $this->getApi('Medias');
	}
}