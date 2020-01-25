<?php
/*______________________*
	Name: gain_access
	Description: This is a file to give access to a user.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
	const BAD_SMS_CODE = "ERR_BAD_SMS_CODE";
	const USER_NOT_FOUND = "ERR_USER_NOT_FOUND";
    const BAD_PHONE_NUMBER = "ERR_BAD_PHONE_NUMBER";
}

// Define Request
class OwlRequest extends OwlRequestMother{
	public $sms_code = null;
	public $phone = null;
	public $ip = null;
	// public $mac_address = null;
}

// Define Response
class OwlResponse extends OwlResponseMother{
    public $access_token = null;
}

// Define Function
function owl_listener($data){
	extract($data->toarray());

	// unifying and validating phone number.
    $phone = Help::unique_phone($phone);
    if (!(Help::validate_phone($phone)))
        return new OwlResponse(ERR::BAD_PHONE_NUMBER);

    // checking that sms_code can be true or not.
    if(!is_numeric($sms_code) or strlen($sms_code) != 4)
        return new OwlResponse(ERR::BAD_SMS_CODE);

    // check if user phone exist in data base
	$search_user=new User();
	$search_user->phone=$phone;
	$users=$search_user->search();

	if(!$users)
	    return new OwlResponse(ERR::USER_NOT_FOUND);

	// checking that user entered the code correctly or not.
	$user=$users[0];
	if(!isset($user->sms_data->code) or $user->sms_data->code != $sms_code)
	    return new OwlResponse(ERR::BAD_SMS_CODE);

	// giving access to user.
	$id = 1;
	if (sizeof($user->access_token) != 0)
	    $id = $user->access_token[sizeof($user->access_token) -1]->id + 1;
    $new_access_token = new AccessToken(md5($user->phone.rand(10000,99999).time()), $ip, $id);
    array_push($user->access_token, $new_access_token);
    $user->sms_data = null;
    //    $user->access_token = new AccessToken(md5($user->phone.rand(10000,99999).time()), $mac_address, );

	if(!$user->update())
	    return new OwlResponse(ERR::INTERNAL);

	return new OwlResponse([
	    "access_token" => $new_access_token
    ]);
}
