<?php

class LERR extends ERRMother{
	const BAD_LISTENER = "ERR_BAD_LISTENER";
	const BAD_JSON = "ERR_BAD_JSON";
}

function listener(){
	$listener = isset($_REQUEST['listener']) ? $_REQUEST['listener'] : "";

	if(!file_exists("listeners/$listener.php"))
		return new OwlResponseMother(LERR::BAD_LISTENER);

	include("listeners/$listener.php");
	
	if(
		!function_exists("owl_listener") or
		!class_exists("OwlRequest") or
		!class_exists("OwlResponse")
	)
		return new OwlResponseMother(LERR::BAD_LISTENER);

	$data = isset($_REQUEST['data']) ? $_REQUEST['data'] : "{}";

	$data = json_decode($data, true);

	if(json_last_error() != JSON_ERROR_NONE)
		return new OwlResponseMother(LERR::BAD_JSON);

	return owl_listener(new OwlRequest($data));
}