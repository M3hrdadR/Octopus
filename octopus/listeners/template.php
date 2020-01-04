<?php
/*______________________*
	Name: template
	Description: This is a template file to learn how to build a listener.
 *______________________*/

// Define Errors
class ERR extends ERRMother{
	// const SOME_ERROR = "ERR_SOME_ERROR";
}

// Define Request
class OwlRequest extends OwlRequestMother{
	// public $some_input = 'default_value';
}

// Define Response
class OwlResponse extends OwlResponseMother{
	// public $some_output = 'default_value';
}

// Define Function
function owl_listener($data){
	extract($data->toarray());
	//
	// listener function here
	//
	return new OwlResponse();
}
