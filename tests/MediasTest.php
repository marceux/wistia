<?php
 
use Wistia\Wistia;

// Load .env variables
Dotenv::load(__DIR__);

class MediasTest extends PHPUnit_Framework_TestCase {

	protected $pass;
	protected $wistia;
	protected $medias;

	protected function setUp()
	{
		$this->pass = $_ENV['WISTIA_PASS'];
		$this->wistia = new Wistia($this->pass);
		$this->medias = $this->wistia->medias();
	}

	public function testList()
	{
		$result = $this->medias->index();

		var_dump($result[0]);
	}
}