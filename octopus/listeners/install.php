<?php
/*______________________*
	Name: template
	Description: This is a template file to learn how to build a listener.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
	const ACCESS_DENIED = "ERR_ACCESS_DENIED";
}

// Define Request
class OwlRequest extends OwlRequestMother{
	public $admin_pass = null;
}

// Define Response
class OwlResponse extends OwlResponseMother{
}

// Define Function
function owl_listener($data){
	extract($data->toarray());
	if ($admin_pass != ADMIN_PASS)
		return new OwlResponse(ERR::ACCESS_DENIED);
	$user = new User();
	$user->rec_table();
	return new OwlResponse();
}
