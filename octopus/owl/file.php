<?php

define("ERR_FILE_NOT_RECEIVED", "ERR_FILE_NOT_RECEIVED");
define("ERR_FILE_BAD", "ERR_FILE_BAD");
define("ERR_FILE_TOO_BIG", "ERR_FILE_TOO_BIG");
define("ERR_FILE_BAD_EXTENSION", "ERR_FILE_BAD_EXTENSION");
define("ERR_FILE_BAD_FORMAT", "ERR_FILE_BAD_FORMAT");
define("ERR_FILE_INTERNAL", "ERR_FILE_INTERNAL");
define("ERR_FILE_INI", "ERR_FILE_INI");

class File{
	public $saved = null;
	public $error = null;

	public function __construct($error, $saved = ""){
		$this->error = $error;
		$this->saved = $saved;
	}
	private static function parse_size($size){
		$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
		$size = preg_replace('/[^0-9\.]/', '', $size);

		if($unit)
			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));

		return round($size);
	}
	public static function save($destination, $input_options = []){
		// set options
		$file_key = isset($input_options["file_key"]) ? $input_options["file_key"] : "file";
		$allowed_types = isset($input_options["allowed_types"]) ? $input_options["allowed_types"] : ["text", "image"];
		$allowed_extensions = isset($input_options["allowed_extensions"]) ? $input_options["allowed_extensions"] : ["txt", "jpg", "jpeg"];
		$max_size = File::parse_size(isset($input_options["max_size"]) ? $input_options["max_size"] : "2M");
		$chmode = isset($input_options["chmode"]) ? $input_options["chmode"] : 0700;
		
		// check if the file upload is allowed
		if(!ini_get('file_uploads'))
			return new File(ERR_FILE_INI);
		if(!ini_get('enable_post_data_reading'))
			return new File(ERR_FILE_INI);
		if(File::parse_size(ini_get('upload_max_filesize')) < $max_size)
			return new File(ERR_FILE_INI);
		if(File::parse_size(ini_get('post_max_size')) < File::parse_size(ini_get('upload_max_filesize')))
			return new File(ERR_FILE_INI);

		// check if the file is sent
		if(!isset($_FILES[$file_key]))
			return new File(ERR_FILE_NOT_RECEIVED);

		// get file data
		$file = $_FILES[$file_key];

		$front_name = strtolower($file["name"]);
		$tmp_name = $file["tmp_name"];
		$size = $file["size"];
		$server_error = $file["error"];

		// check if server received the file
		if($server_error !== 0)
			return new File(ERR_FILE_BAD);

		// TODO attack handling
		
		// check if the file size is allowed
		if($size > $max_size)
			return new File(ERR_FILE_TOO_BIG);

		// check if the file extension is allowed
		$front_name = explode(".", $front_name);
		$extension = end($front_name);
		if(!in_array($extension, $allowed_extensions))
			return new File(ERR_FILE_BAD_EXTENSION);

		// check if the file format is allowed
		// TODO
		
		// check if the destination address doesn't exist
		$destination = "uploads/$destination.$extension";
		if(file_exists($destination))
			return new File(ERR_FILE_INTERNAL);

		// finally move the file
		if(!file_exists('uploads'))
			mkdir('uploads', 0777, true);
		
		if(!move_uploaded_file($tmp_name, $destination))
			return new File(ERR_FILE_INTERNAL);
		return new File(null, $destination);
	}
}
