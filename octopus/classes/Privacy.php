<?php

class Privacy{
    public $array_of_features = null;
    public $username = null;
    public $title = null;
    public $fname = null;
    public $lname = null;
    public $email = null;
    public $phone = null;
    public $image = null;
    public $bio = null;
    public $url = null;
    public $birth_date = null;
    public $gender = null;
    public $national_id = null;
    // address
    public $geolocation = null;
    public $city_code = null;
    public $address = null;
    public $postal_code = null;
    // education
    public $personal_code = null;
    public $education_school = null;
    public $education_field = null;
    public $education_grade = null;
    // job
    public $job_organization = null;
    public $job_position = null;
    public $job_field = null;
    function __construct(){
        $this->array_of_features = array(
            "username"=> 1,
            "title"=> 1,
            "fname"=> 1,
            "lname"=> 1,
            "email"=> 1,
            "phone"=> 1,
            "image"=> 1,
            "bio"=> 1,
            "url"=> 1,
            "birth_date"=> 1,
            "gender"=> 1,
            "national_id"=> 1,
            "geolocation"=> 1,
            "city_code"=> 1,
            "address"=> 1,
            "postal_code"=> 1,
            "personal_code"=> 1,
            "education_school"=> 1,
            "education_field"=> 1,
            "education_grade"=> 1,
            "job_organization"=> 1,
            "job_position"=> 1,
            "job_field"=> 1,
            "job_start_year"=> 1,
            "cases"=> 2,
            "owner_cases"=> 2,
            "admin_cases"=> 2,
            "member_cases"=> 2,
            "contacts"=> 2,
            "privacy"=> 2,
            "access_token"=> 2,
            "score"=> 2,
            "join_date"=> 2,
            "sms_data"=> 2,
            "id"=> 1
        );
    }
}