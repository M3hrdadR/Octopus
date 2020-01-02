<?php
/*______________________*
	Name: log_out
	Description: This is a file that will be called when someone wants to log out.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
    const WRONG_MAC_ADDRESS = "ERR_WRONG_MAC_ADDRESS";
    const BAD_PHONE_NUMBER = "ERR_BAD_PHONE_NUMBER";
	// const SOME_ERROR = "ERR_SOME_ERROR";
}

// Define Request
class OwlRequest extends OwlRequestMother{
    public $phone = null;
    public $mac_address = null;
    // public $some_input = 'default_value';
}

// Define Response
class OwlResponse extends OwlResponseMother{
	// public $some_output = 'default_value';
}

// Define Function
function owl_listener($data){
	extract($data->toarray());

	// TODO validate mac_address

    // Making things ready to search in db.
    $phone = Help::unique_phone($phone);
    if (!(Help::validate_phone($phone)))
        return new OwlResponse(ERR::BAD_PHONE_NUMBER);

    $search_user=new User();
    $search_user->phone = $phone;
    $search_user->access_token->mac_address = $mac_address;
    $search_user->access_token->token = null;
    $users = $search_user->search();

    if(!$users)
        return new OwlResponse(ERR::WRONG_MAC_ADDRESS);

    // Deleting user's token from db.
    $user = $users[0];
    $user->access_token = null;
    if(!$user->update())
        return new OwlResponse(ERR::INTERNAL);
	return new OwlResponse();
}
