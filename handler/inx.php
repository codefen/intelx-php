<?php

	// INX HANDLER
	
	// This helper is based off the IntelX API documentation, implementing some of the endpoint shown on it
	// https://github.com/IntelligenceX/SDK/blob/master/Intelligence%20X%20API.pdf



	// Base data / functions

	function curlOptions($url, $apiKey, $requestData = null, $isPost = false) {
		$options = [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_HTTPHEADER => [
				'x-key: ' . $apiKey,
			],
		];

	
		if ($isPost) {
			$options[CURLOPT_CUSTOMREQUEST] = 'POST';
			$options[CURLOPT_POSTFIELDS] = $requestData;
			$options[CURLOPT_HTTPHEADER] = [
				'x-key: ' . $apiKey,
				'Content-Type: application/json',
			];
		}

		if (!$isPost) {
			$options[CURLOPT_CUSTOMREQUEST] = 'GET';
		}

		return $options;
	}
	
	function execCurl($curlOptions) {
		$curl = curl_init();
		curl_setopt_array($curl, $curlOptions);
		$response = curl_exec($curl);
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
		if (curl_errno($curl) || $httpCode !== 200) {
			return null;
		}
	
		curl_close($curl);
		return $response;
	}
	


	// Stats function
	// /intelligent/search/statistic

	function statsIntelx($id, $apiKey, $apiUrl) {
		// BUILD REQUEST DATA
		$statsUrl = $apiUrl . 'intelligent/search/statistic';
		$params = ['id' => $id];
		$requestData = http_build_query($params);
	
		// CURL REQUEST
		$curlOptions = curlOptions($statsUrl . '?' . $requestData, $apiKey, '', false);
		$response = execCurl($curlOptions);
	
		return $response ? json_decode($response, true) : null;
	}
	


	// Search functions
	// /intelligent/search

	if ($_REQUEST['ac']  == 'init_search') {
		$term = addslashes($_REQUEST['term']);
	
		// INPUT VALIDATION
		if (!is_string($term) || strlen($term) > 255) {
			$feedback->error = '1';
			$feedback->info = 'Invalid term value';
			deliver($feedback);
		}
	
		// BUILD REQUEST DATA
		$searchParams = [
			'term' => $term,
			'maxresults' => 1000,
			'media' => 0,
			'sort' => 2,
			'terminate' => [],
		];
	
		$requestData = json_encode($searchParams);
		$searchCurlOptions = curlOptions($apiUrl . 'intelligent/search', $apiKey, $requestData, true);
		$searchResponse = execCurl($searchCurlOptions);
		if ($searchResponse === null) {
			$feedback->error = '1';
			$feedback->info = 'Error making API request';
			deliver($feedback);
		}
	
		$data = json_decode($searchResponse, true);
	
		$stats = statsIntelx($data['id'], $apiKey, $apiUrl);
		$id = $data['id'];
		$count = $stats['type'][0]['count'];
	
		$feedback->response = [
			'id' => $id,
			'count' => $count,
		];
		$feedback->term = $term;
	
		// RETURNS FEEDBACK
		deliver($feedback);
	}

	if ($_REQUEST['ac']  == 'search') {
		$id = addslashes($_REQUEST['id']);
		$offset = addslashes($_REQUEST['offset']);
	
		$params = [
			'id' => $id,
			'limit' => 16,
			'offset' => $offset,
			'statistics' => 0,
			'previewlines' => 8
		];
	
		$url = $apiUrl . 'intelligent/search/result?' . http_build_query($params);
		$curlOptions = curlOptions($url, $apiKey, '', false);
		$response = execCurl($curlOptions);

		if ($response === null) {
			$feedback->error = '1';
			$feedback->info = 'Error making API request';
			deliver($feedback);
		}
	
		$resultData = json_decode($response, true);
		$records = $resultData['records'];
		$resultsWithPreviews = [];
	
		foreach ($records as $record) {
			$result = [
				'name' => $record['name'],
				'date' => $record['date'],
				'bucket_data' => $record['bucketh'],
				'bucket_id' => $record['bucket'],
				'storage_id' => $record['storageid'],
				'media_id' => $record['media']
			];
	
			$resultsWithPreviews[] = $result;
		}
	
		$feedback->response = $resultsWithPreviews;
		deliver($feedback);
	}

	if ($_REQUEST['ac']  == 'preview') {

		$sid = addslashes($_REQUEST['sid']);
		// MediaID
		$mid = addslashes($_REQUEST['mid']);
		// BucketID
		$bid = addslashes($_REQUEST['bid']);
	
		$params = [
			'sid' => $sid,
			'f' => 0,
			'l' => 8,
			'c' => 1,
			'm' => $mid,
			'b' => $bid,
			'k' => $apiKey
		];
		
		$url = $apiUrl . 'file/preview?' . http_build_query($params);
		$curlOptions = curlOptions($url, $apiKey, '', false);
		$response = execCurl($curlOptions);

		if ($response === null) {
			$feedback->error = '1';
			$feedback->info = 'Error making API request';
			deliver($feedback);
		}
	
		$resultData = json_decode($response, true);

	
		$feedback->response = $resultData;
		deliver($feedback);
	}
	
	if ($_REQUEST['ac']  == 'read') {

		$sid = addslashes($_REQUEST['sid']);
		// BucketID
		$bid = addslashes($_REQUEST['bid']);
	
		$params = [
			'f' => 0,
			'storageid' => $sid,
			'bucket' => $bid,
			'k' => $apiKey,
			'license' => 'api'
		];
		
		$url = $apiUrl . 'file/view?' . http_build_query($params);
		$curlOptions = curlOptions($url, $apiKey, '', false);
		$response = execCurl($curlOptions);
		if ($response === null) {
			$feedback->error = '1';
			$feedback->info = 'Error making API request';
			deliver($feedback);
		}
	
		$resultData = json_decode($response, true);

	
		$feedback->response = $resultData;
		deliver($feedback);
	}
?>