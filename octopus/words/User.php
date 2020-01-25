<?php
/*______________________*
	Name: user
	Description: This is a file to express user data structure.
 *______________________*/

class User extends OwlWordMother{
	// identity info
	public $username;			const username_type = "VARCHAR(30)";
	public $title;				const title_type = "VARCHAR(100)";
	public $fname;				const fname_type = "VARCHAR(60)";
	public $lname;				const lname_type = "VARCHAR(60)";
	public $email;				const email_type = "VARCHAR(256)";
	public $phone;				const phone_type = "VARCHAR(20)";
	public $image;				const image_type = "TEXT";
	public $bio;				const bio_type = "VARCHAR(200)";
	public $url;				const url_type = "TEXT";
	public $birth_date;			const birth_date_type = "DATETIME";
	public $gender;				const gender_type = "INT(1) UNSIGNED";
	public $national_id;		const national_id_type = "VARCHAR(10)";
	// address
    public $geolocation;        const geolocation_type = "JSONOBJECT";
	public $city_code;			const city_code_type = "INT UNSIGNED";
	public $address;			const address_type = "TEXT";
	public $postal_code;		const postal_code_type = "VARCHAR(11)";
	// education
    public $personal_code;      const personal_code_type = "VARCHAR(20)";
	public $education_school;	const education_school_type = "VARCHAR(100)";
	public $education_field;	const education_field_type = "VARCHAR(100)";
	public $education_grade;	const education_grade_type = "VARCHAR(100)";
	// job 
	public $job_organization;	const job_organization_type = "INT UNSIGNED";
	public $job_position;		const job_position_type = "INT UNSIGNED";
	public $job_field;			const job_field_type = "INT UNSIGNED";
	public $job_start_year;		const job_start_year_type = "INT UNSIGNED";
	// other info 
	public $access_token;		const access_token_type = "JSONARRAY";
	public $cases;				const cases_type = "JSONARRAY";
    public $score;				const score_type = "INT UNSIGNED";
    public $join_date;			const join_date_type = "DATETIME";
    public $sms_data;			const sms_data_type = "JSONOBJECT";
}
