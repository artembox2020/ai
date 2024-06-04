<?php

	header('Content-Type: text/plain;');

	$DOMAIN_URL = $_REQUEST['domain'];//"https://notifier.pp.ua";
	const FOLDER = "aiapi";

	function removeDir(string $dir): void {
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
                 RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()){
				rmdir($file->getPathname());
			} else {
				unlink($file->getPathname());
			}
		}
		rmdir($dir);
	}

	function file_get_content($url)
	{
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	    $res = curl_exec($ch);
	    curl_close($ch);

	    return $res;
	}

	$neuralWriter = file_get_content($DOMAIN_URL . "/ajax/get-file?file=../../common/components/ai/NeuralWriter.php");
	$providerBuilder = file_get_content($DOMAIN_URL . "/ajax/get-file?file=../../common/components/ProviderBuilder.php");
    $paraphrase = file_get_content($DOMAIN_URL . "/ajax/get-file?file=paraphrase.php");

    $usePattern = "/(\s+|^)use\s.*?\;/";
    $neuralWriter = preg_replace($usePattern, "", $neuralWriter);
    $providerBuilder = preg_replace($usePattern, "", $providerBuilder);
    
    $usePattern = "/(\s+|^)namespace\s.*?\;/";
    $neuralWriter = preg_replace($usePattern, "", $neuralWriter);
    $providerBuilder = preg_replace($usePattern, "", $providerBuilder);

	if (file_exists(FOLDER)) {
		removeDir(FOLDER);
	}

	mkdir(FOLDER, 0777, true);

    file_put_contents(FOLDER . "/NeuralWriter.php", $neuralWriter);
    file_put_contents(FOLDER . "/ProviderBuilder.php", $providerBuilder);
    file_put_contents(FOLDER . "/paraphrase.php", $paraphrase);

    unlink("initapi.php");
