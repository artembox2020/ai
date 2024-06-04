<?php

namespace common\components;

class ProviderBuilder {

    public static function getCurl($providerName, $providerUrl, $api_key, $url, $data, &$err)
    {
        $providerUrl = str_replace(["{url}", "{api_key}"], [$url, $api_key], $providerUrl);

        $ch = curl_init($providerUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

		$ch = self::updateCurlByProvider($ch, $providerName);

		$response = curl_exec($ch);

		curl_close($ch);

		if (!empty($response)) {
			$err = false;

			return $response;
		}

		$err = true;

		return null;
    }

    public static function updateCurlByProvider($ch, $providerName)
    {
        $httpHeader = ['Content-Type: application/json'];

        switch ($providerName) {
            case 'ScrapingAnt':
                $httpHeader[] = 'Ant-Content-Type: application/json';
                break;
            default:
                break;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);

        return $ch;
    }
}