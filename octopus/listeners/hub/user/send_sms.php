<?php
/*______________________*
	Name: send_sms
	Description: This is a file to send_sms to user.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
	const TOO_MANY_REQUESTS = "ERR_TOO_MANY_REQUESTS";
	const BAD_PHONE_NUMBER = "ERR_BAD_PHONE_NUMBER";
}

// Define Request
class OwlRequest extends OwlRequestMother{
    public $phone = null;
}

// Define Response
class OwlResponse extends OwlResponseMother{

}

// Define Function
function owl_listener($data){
	extract($data->toarray());

	// unifying and validating phone number.
    $phone = Help::unique_phone($phone);
	if (!(Help::validate_phone($phone)))
	    return new OwlResponse(ERR::BAD_PHONE_NUMBER);


//    $sms_code=rand(1000,9999);
    $sms_code = 1234;
    // check if user exists

    $search_user=new User();
    $search_user->phone=$phone;
    $users=$search_user->search();

    // in this case user exists so we update it
    if($users){
        $user=$users[0];
        if(isset($user->sms_data->generation_time) and time() - $user->sms_data->generation_time <= 60)
            return new OwlResponse(ERR::TOO_MANY_REQUESTS);

        $user->sms_data = new SmsData($sms_code);
        if(!$user->update())
            return new OwlResponse(ERR::INTERNAL);
    }

    // in this case user does not exists so we make one
    else{
        $user=new User();
        $user->phone=$phone;
        $user->sms_data=new SmsData($sms_code);
        if(!$user->insert())
            return new OwlResponse(ERR::INTERNAL);
    }

    // TODO send sms
	return new OwlResponse();
}
