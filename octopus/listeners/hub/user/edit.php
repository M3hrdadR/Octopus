<?php
/*______________________*
	Name: template
	Description: This is a template file to learn how to build a listener.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
    const ACCESS_DENIED = "ERR_ACCESS_DENIED";
    const BAD_USERNAME = "ERR_BAD_USERNAME";
    const BAD_FNAME = "ERR_BAD_FNAME";
    const BAD_LNAME = "ERR_BAD_LNAME";
    const BAD_EMAIL = "ERR_BAD_EMAIL";
    const BAD_IMAGE = "ERR_BAD_IMAGE";
    const BAD_BIO = "ERR_BAD_BIO";
    const BAD_URL = "ERR_BAD_URL";
    const BAD_BIRTH_DATE = "ERR_BAD_BIRTH_DATE";
    const BAD_GENDER = "ERR_BAD_GENDER";
    const BAD_NATIONAL_ID = "ERR_BAD_NATIONAL_ID";

}

// Define Request
class OwlRequest extends OwlRequestMother{
    public $access = null;
    public $new_user = null;
}

// Define Response
class OwlResponse extends OwlResponseMother{
}

// Define Function
function owl_listener($data){
    extract($data->toarray());

    // validating user.
    if (!Help::validate_user_2($access))
        return new OwlResponse(ERR::ACCESS_DENIED);

    // finding user itself, surely he exist.
    $search_user = new User();
    $search_user->phone = $access["phone"];
    $users=$search_user->search();
    $user = $users[0];


    foreach ($new_user as $key => $value) {
        if (isset($value)) {
            if ($key == "username") {
                if (!Help::verify_username($value))
                    return new OwlResponse(ERR::BAD_USERNAME);
                $user->username = $value;
            }
            elseif ($key == "title") {

                $user->title = $value;
            }
            elseif ($key == "fname") {
                if (!Help::verify_name($value))
                    return new OwlResponse(ERR::BAD_FNAME);
                $user->fname = $value;
            }
            elseif ($key == "lname") {
                if (!Help::verify_name($value))
                    return new OwlResponse(ERR::BAD_LNAME);
                $user->lname = $value;
            }
            elseif ($key == "email") {
                if (!Help::verify_email($value))
                    return new OwlResponse(ERR::BAD_EMAIL);
                $user->email = $value;
            }
            // TODO
            elseif ($key == "image") {
                $user->image = $value;
            }
            elseif ($key == "bio") {
                if (!Help::verify_bio($value))
                    return new OwlResponse(ERR::BAD_BIO);
                $user->bio = $value;
            }
            elseif ($key == "url") {
                if (!Help::verify_url($value))
                    return new OwlResponse(ERR::BAD_URL);
                $user->url = $value;
            }
            elseif ($key == "birth_date") {
                if (!Help::verify_birth_date($value))
                    return new OwlResponse(ERR::BAD_BIRTH_DATE);
                $user->birth_date = $value;
            }
            elseif ($key == "gender") {
                if (!Help::verify_gender($value))
                    return new OwlResponse(ERR::BAD_GENDER);
                $user->gender = $value;
            }
            elseif ($key == "national_id") {
                if (!Help::verify_national_id($value))
                    return new OwlResponse(ERR::BAD_NATIONAL_ID);
                $user->national_id = $value;
            }
        }
    }

    // applying changes to db.
    if(!$user->update())
        return new OwlResponse(ERR::INTERNAL);


    return new OwlResponse();
}
