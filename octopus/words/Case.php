<?php
/*______________________*
	Name: template
	Description: This is a template file to learn how to build a Word.
 *______________________*/

class Case extends OwlWordMother{
	public $case_id;			        const case_id = "VARCHAR(50)";
	public $case_name;			        const case_name = "VARCHAR(20)";
	public $creation_date;			    const creation_date = "DATETIME";
	public $case_type;			        const case_type = "JSONOBJECT";
	public $image;			            const image = "TEXT";
	public $case_description;			const case_description = "TEXT";
	public $case_content;			    const case_content = "JSONOBJECT";
}
