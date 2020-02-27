<?php
/*______________________*
	Name: get
	Description: This is a file to get information about others (or user himself).
 *______________________*/

// Define Errors
class ERR extends ERRMother{
    const ACCESS_DENIED = "ERR_ACCESS_DENIED";
    // const SOME_ERROR = "ERR_SOME_ERROR";
}

// Define Request
class OwlRequest extends OwlRequestMother{
    public $access = null;
    public $id = null;
    public $full = null;
}

// Define Response
class OwlResponse extends OwlResponseMother{
    public $information = null;
}

// Define Function
function owl_listener($data){
    extract($data->toarray());

    // validating user.
    if (!Help::validate_user_2($access))
        return new OwlResponse(ERR::ACCESS_DENIED);

    // main returning array.
    $passing_array = array();


    // finding user itself, surely he exist.
    $search_user = new User();
    $search_user->phone = $access["phone"];
    $users=$search_user->search();
    $user = $users[0];

    // finding user we want his info.
    $wanted_user = new User($id);

    // returning information.
    // if the person who we are locking for doesn't exist.
    if (!isset($id) or !isset($wanted_user->phone)) {
        if ($full)
            $passing_array = $user;
        else {
            foreach ($user as $key => $value) {
                if ($key == "title" or $key == "image" or $key == "bio" or $key == "cover")
                    array_push($passing_array, array($key => $value));
            }
        }
    }
    // if the person who we are looking for exists.
    else {
        if ($full) {
            foreach ($wanted_user as $key => $value) {
                if ($wanted_user->privacy->array_of_features->$key == 0) {
                    array_push($passing_array, array($key => $value));
                }
                elseif ($wanted_user->privacy->array_of_features->$key == 1){
                    if (in_array($user->id, $wanted_user->contacts))
                        array_push($passing_array, array($key => $value));
                }
            }
        }
        else {
            foreach ($wanted_user as $key => $value) {
                if ($key == "title" or $key == "image"){
                    if ($wanted_user->privacy->array_of_features->$key == 0) {
                        array_push($passing_array, array($key => $value));
                    }
                    elseif ($wanted_user->privacy->array_of_features->$key == 1){
                        if (in_array($user->id, $wanted_user->contacts))
                            array_push($passing_array, array($key => $value));
                    }
                }
            }
        }
    }
    return new OwlResponse(["information" => $passing_array]);
}
