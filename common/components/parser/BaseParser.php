<?php

namespace common\components\parser;

class BaseParser {

    public static function initCurl($endpoint, $params, $apiKey, $name, $proxy = null, $post = false)
    {
		if (!empty($params)) {
			$ch = curl_init();

			if (empty($post)) {
			    foreach ($params as $key => $param) {
				    $params[$key] = urlencode($param);
			    }
			}

			//echo "<br/> ---inside initCurl---<br/> fullEndpoint:" . $endpoint . "?" . http_query($params) . "<br/>";
			if (empty($post)) {
			    curl_setopt($ch, CURLOPT_URL, $endpoint . "?" . http_build_query($params));
			} else {
			    curl_setopt($ch, CURLOPT_URL, $endpoint);
			}
		} else {
			$ch = curl_init($endpoint);
		}

		if (!empty($proxy)) {
			curl_setopt($ch, CURLOPT_PROXY, $proxy);
		}

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);

		if (!empty($post)) {
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

		return $ch;
	}
}