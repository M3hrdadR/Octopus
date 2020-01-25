<?php
/*______________________*
	Name: log_out
	Description: This is a file that will be called when someone wants to log out.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
    const USER_NOT_FOUND = "ERR_USER_NOT_FOUND";
    const BAD_PHONE_NUMBER = "ERR_BAD_PHONE_NUMBER";
    const ACCESS_DENIED = "ERR_ACCESS_DENIED";
    const BAD_ID_NUMBER = "ERR_BAD_ID_NUMBER";
	// const SOME_ERROR = "ERR_SOME_ERROR";
}

// Define Request
class OwlRequest extends OwlRequestMother{
    public $phone = null;
    public $token = null;
    public $id = null;
    // public $some_input = 'default_value';
}

// Define Response
class OwlResponse extends OwlResponseMother{
	// public $some_output = 'default_value';
}

/*  Todo : its possible that md5 produce a token starting with 0 which will be a problem tht can be fixed by converting it
        to string but we should unique to work easier with that. */

// Define Function
function owl_listener($data){
	extract($data->toarray());

    // Making things ready to search in db.
    $phone = Help::unique_phone($phone);
    if (!(Help::validate_phone($phone)))
        return new OwlResponse(ERR::BAD_PHONE_NUMBER);

    $search_user=new User();
    $search_user->phone = $phone;
    $users = $search_user->search();


    if(!$users)
        return new OwlResponse(ERR::USER_NOT_FOUND);

    // checking that user have access or not.
    $user = $users[0];
    $user_have_access = Help::validate_user($user, $token);
    if(!$user_have_access)
        return new OwlResponse(ERR::ACCESS_DENIED);


    // checking that the device exists in the list of devices.
    if ($id > count($user->access_token))
        return BAD_ID_NUMBER;

    // Removing user's token from db and then renumbering ides.
    array_splice($user->access_token, $id - 1, 1);
    for ($i = 0; $i < count($user->access_token); $i++) {
        $user->access_token[$i]->id = $i + 1;
    }


    if(!$user->update())
        return new OwlResponse(ERR::INTERNAL);
	return new OwlResponse();
}
