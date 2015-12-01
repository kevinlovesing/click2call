<?php
//require_once "mis_account.php";
//phpinfo();

//////////////////////////////////////////////////////////
$ch = curl_init();
//$api_request_url = 'http://mcc-ka-api.sankuai.info/v1/';
$api_request_url = 'http://192.168.181.24:8000/v1/';
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
/////////////////////////////////////////////////////////////
$recording_path = 'http://mcc-record01.wj.sankuai.info/recording';
$account_id = '6ab3af15f6abced52e10129934d7207b';
$api_request_url_accounts = $api_request_url.'accounts/'.$account_id.'/' ;
//$api_key  = '6d24b64c26d57bedbfa4e814c409e370ffd9237610fa6a37d14cd1f1454d6973';
//$api_key  = 'f08ac9e5bc8774d61f23f569ce90f3a530fe98e4b513138eb370338de0f7660c';
$api_key = '0b454cfa81f4fb1568a564b2cfca4ace9f42c74f8ae7fbca37612fecec87be98';
/////////////////////////////////////////////////////////

$auth_token = generate_authtoken();
echo "auth token is ".$auth_token."\n" ;

//$name = 'meituan';
//$username = '3007';
$password = 'mt1234';
//$click2call_id = '959988266db73ffb7c8db5618817685d';
$click2call_id = '5644e915612b07a1b02fa4627ab7756d';
$contact = '57376600';
//get_all_click2call();
//add_click2call("test","18611905667","56309100");
active_click2call($click2call_id, $contact);
/*
foreach($array_mis_account as $name => $username){
	global $password ;
//	echo $name."=>".$username."\n" ;
//	add_mis_user($name, $username, $password);
	delete_mis_user($name, $username);

}
 */

curl_close($ch);

function generate_authtoken(){
	global $ch ;
	global $api_key ;
	global $api_request_url ; 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	$api_request_url_auth = $api_request_url.'api_auth' ;
	curl_setopt($ch, CURLOPT_URL, $api_request_url_auth);
	$data_json = '{ "data" : { "api_key" : "'.$api_key.'" }}' ;
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	$api_response = curl_exec($ch);
	$api_response_info = curl_getinfo($ch);
	$api_response_body = substr($api_response, $api_response_info['header_size']);

	$response_body_obj = json_decode($api_response_body, TRUE) ;
//	var_dump($response_body_obj);
	$auth_token = $response_body_obj[auth_token];
	return $auth_token;
}


function get_all_click2call(){

	global $ch ;
	global $auth_token ;
	global $api_request_url_accounts ;
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $api_request_url_cdr = $api_request_url_accounts.'clicktocall' ;
	curl_setopt($ch, CURLOPT_URL, $api_request_url_cdr);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$auth_token));
	
	$api_response = curl_exec($ch);
	$api_response_info = curl_getinfo($ch);
	$api_response_body = substr($api_response, $api_response_info['header_size']);
	$response_body_obj = json_decode($api_response_body, TRUE) ;
	var_dump($response_body_obj);

//	$user_id = $response_body_obj[data][0][id];
//	return $user_id;


}

function add_click2call($name, $extension, $callerid){

	global $ch ;
	global $auth_token ;
	global $api_request_url_accounts ;
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        $api_request_url_cdr = $api_request_url_accounts.'clicktocall' ;
	curl_setopt($ch, CURLOPT_URL, $api_request_url_cdr);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$auth_token));

        $data_json = '{"data":{"name":"'.$name.'","extension":"'.$extension.'","caller_id_number":"'.$callerid.'"},"verb":"PUT"}';
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);

	$api_response = curl_exec($ch);
	$api_response_info = curl_getinfo($ch);
	$api_response_body = substr($api_response, $api_response_info['header_size']);
	$response_body_obj = json_decode($api_response_body, TRUE) ;
	var_dump($response_body_obj);

//	$user_id = $response_body_obj[data][0][id];
//	return $user_id;


}

function active_click2call($click2call_id, $contact){

	global $ch ;
	global $auth_token ;
	global $api_request_url_accounts ;
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        $api_request_url_cdr = $api_request_url_accounts.'clicktocall/'.$click2call_id.'/connect' ;
	curl_setopt($ch, CURLOPT_URL, $api_request_url_cdr);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$auth_token));
	
	$data_json = '{"data":{"contact":"'.$contact.'"},"verb":"POST"}';
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);

	$api_response = curl_exec($ch);
	$api_response_info = curl_getinfo($ch);
	$api_response_body = substr($api_response, $api_response_info['header_size']);
	$response_body_obj = json_decode($api_response_body, TRUE) ;
	var_dump($response_body_obj);

//	$user_id = $response_body_obj[data][0][id];
//	return $user_id;


}
function get_cdr_by_username($username, $created_from, $created_to){

	global $ch ;
	global $auth_token ;
	global $api_request_url_accounts ;
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$userid = get_user_id_by_username($username) ;
        $api_request_url_users_cdr = $api_request_url_accounts.'users/'.$userid.'/cdrs?'.'created_from='.$created_from.'&created_to='.$created_to ;
       // $api_request_url_users_cdr = $api_request_url_accounts.'users/'.$userid.'/cdrs?'.'created_from='.$created_from ;
	echo $api_request_url_users_cdr ;
	curl_setopt($ch, CURLOPT_URL, $api_request_url_users_cdr);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$auth_token));
	
	$api_response = curl_exec($ch);
	$api_response_info = curl_getinfo($ch);
	$api_response_body = substr($api_response, $api_response_info['header_size']);
	$response_body_obj = json_decode($api_response_body, TRUE) ;
	var_dump($response_body_obj);

//	$user_id = $response_body_obj[data][0][id];
//	return $user_id;


}

function get_user_id_by_username($username){

	global $ch ;
	global $auth_token ;
	global $api_request_url_accounts ;
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $api_request_url_users = $api_request_url_accounts.'users?filter_username='.$username ;
	curl_setopt($ch, CURLOPT_URL, $api_request_url_users);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: '.$auth_token));
	
	$api_response = curl_exec($ch);
	$api_response_info = curl_getinfo($ch);
	$api_response_body = substr($api_response, $api_response_info['header_size']);
	$response_body_obj = json_decode($api_response_body, TRUE) ;
//	var_dump($response_body_obj);

	$user_id = $response_body_obj[data][0][id];
	return $user_id;


}



?>
