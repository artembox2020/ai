<?php

namespace common\components\ai;

use common\components\ProviderBuilder;

class NeuralWriter {

	public static function t($providerName, $providerUrl, $api_key, $text, $from, $to, &$err)
	{
		$data = compact('text', 'from', 'to');
		$url = "https://api2.neuralwriter.com/translate";

		return ProviderBuilder::getCurl($providerName, $providerUrl, $api_key, $url, $data, $err);
	}

	public static function paraphrase($providerName, $providerUrl, $api_key, $text, $from, &$err)
	{
		$data = compact('text', 'from');
		$url = "https://api2.neuralwriter.com/rewrite-light";

		return ProviderBuilder::getCurl($providerName, $providerUrl, $api_key, $url, $data, $err);
	}

	public static function detectAi($providerName, $providerUrl, $api_key, $text, $from, &$err)
	{
		$data = compact('text', 'from');
		$url = "https://neuralwriter.com/api/content-detector/";

		return ProviderBuilder::getCurl($providerName, $providerUrl, $api_key, $url, $data, $err);
	}
}