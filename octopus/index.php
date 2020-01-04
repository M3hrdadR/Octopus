<?php
/*______________________*
	OWL API FRAMEWORK v1.1.1
	Date: 1398/09/26
	URL: http://owl.kamyar.mirzavaziri.com
	Designed and Developed by Kamyar Mirzavaziri
	Author URL: http://kamyar.mirzavaziri.com
 *______________________*/

require "config.php";

// ******************************* OWL+ *******************************
	// Owl
	foreach (glob("owl/*.php") as $owl)
		require($owl);

	// 3rd Party APIs, Libraries and Plugins
	require("resources/requirements.php");

	// Dev-Defined Classes
	spl_autoload_register(function($class){
		if(file_exists("classes/$class.php"))
			include("classes/$class.php");
	});
// ******************************* WORD *******************************
	spl_autoload_register(function($word){
		if(file_exists("words/$word.php"))
			include("words/$word.php");
	});
// ***************************** LISTENER *****************************
	require "listener.php";
	listener()->respond();
