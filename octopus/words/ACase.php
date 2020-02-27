<?php
/*______________________*
	Name: ACase
	Description: This is a file to express Case data structure.
 *______________________*/

class ACase extends OwlWordMother{
    public $case_identity;			    const case_identity = "VARCHAR(50)";
    public $case_name;			        const case_name = "VARCHAR(20)";
    public $creation_date;			    const creation_date = "DATETIME";
    public $case_type;			        const case_type = "JSONOBJECT";
    public $image;			            const image = "TEXT";
    public $case_description;			const case_description = "TEXT";
    public $case_content;			    const case_content = "JSONOBJECT";
}