<?php
/*
	No need to get OOP here

	slack api methods:
	1. users.list
	2. channels.history
		channel => ID;
		oldest => timestamp (if needed)
	
	3. channels.list
		exclude_members => true  

*/
function getArchive($options) {
	$archive_name = !empty($options['channel']) ? $options['archive_name'] . '/' . $options['channel'] : $options['archive_name'];
	$json_data = requestArchiveFromFile($archive_name);
	if((!empty($options['update']) && $options['update']) || !$json_data) {
		$json_data = requestArchiveFromAPI($options);
		createArchiveFile($archive_name, $json_data);
	}
	return $json_data;
}
function requestArchiveFromFile($archive_name, $decode = false) {
	$file_path = dirname(__FILE__).'/../archives/'.$archive_name.'/archive.json';
	if(file_exists($file_path)) {
		$json_data = file_get_contents($file_path);
		if($decode) return json_decode($json_data, true);
		return $json_data;
	}
	return false;
}
function requestArchiveFromAPI($options) {
	$fns = [
		'usersList' => 'requestUsersList',
		'channelsList' => 'requestChannelsList',
		'channelsHistory' => 'requestChannelsArchive'
	];
	if(!empty($fns[$options['archive_name']])) return $fns[$options['archive_name']]($options);
	return false;
}
function requestUsersList() {
	return requestJSONResponse([], 'users.list');
}
function requestChannelsList() {
	return requestJSONResponse(['exclude_members' => true], 'channels.list');
}
function requestChannelsArchive($options) {
	unset($options['archive_name']);
	return requestJSONResponse($options, 'channels.history');
}
function requestJSONResponse($options = [], $method) {
	if(empty($method)) exit('method can not be empty');
	if(empty($options['token'])) $options['token'] = "xoxp-309340015120-309454432033-311952132791-3842d11bc26080847d8d624dc5b38b3a";

	$ch = curl_init("https://slack.com/api/".$method);
	$data = http_build_query($options);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
	curl_close($ch);
	return $result;
}
function createArchiveFile($archive_name, $json_data) {
	if(!is_dir(dirname(__FILE__).'/../archives/'.$archive_name.'/')) mkdir(dirname(__FILE__).'/../archives/'.$archive_name.'/',0755,true);
	$file_path = dirname(__FILE__).'/../archives/'.$archive_name.'/archive.json';
	return file_put_contents($file_path, $json_data);
}
function getFilesFromDirectory($archive_name) {
	$directory = dirname(__FILE__).'/../archives/'.$archive_name.'/';
	return array_diff(scandir($directory), array('..', '.'));
}

