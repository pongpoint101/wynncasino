<?php
class Vizpay
{
	private $api_key;
	private $secret_key;
	private $version;
	public $api_url;
	public $callback_url;

	function __construct($config)
	{
		$this->api_key = $config['api_key'];
		$this->secret_key = $config['secret_key'];
		$this->version = $config['version'];
		$this->api_url = $config['api_url'];
		$this->callback_url = $config['callback_url'];
	}

	public function gen_signature($array_data)
	{
		// Add Secret Key to object or array parameters 
		$array_data['key'] = $this->secret_key;

		// Sort the key parameters alphabetically in ascending order and convert them into JSON string.
		ksort($array_data);
		$json_string = json_encode($array_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

		// Use JSON string encrypted to MD5 and you get signature
		$signature = MD5($json_string);

		return $signature;
	}
	/*
	*	$method : GET, POST
	*/
	public function call_url($path, $method, $array_data)
	{
		$curl = curl_init();

		$url = $this->api_url . $this->version . $path;

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => json_encode($array_data),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . base64_encode($this->api_key . ":"),
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response, true);
	}
}
