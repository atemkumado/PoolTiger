<?php

namespace App\Constants;

class VtigerConstant
{
    const CITY_FIELD = "City";
    const COUNTRY_FIELD = "Country";
    const DISTRICT_FIELD = "District";
    const DOB_FIELD = "Date of Birth";
    const ASSIGNED_TO_FIELD = "Assigned to";
    const FIRST_NAME_FIELD = "First Name";
    const LAST_NAME_FIELD = "Last Name";
    const MOBILE_PHONE_FIELD = "Mobile Phone";
    const LK_MOBILE_PHONE_FIELD = "LK Mobile Phone";
    const TOP_1_PROGRAMMING_LANGUAGE_FIELD = "Top 1 Programming Language";
    const TOP_1_FRAMEWORK_FIELD = "Top 1 Framework";
    const TOP_1_DATABASE_FIELD = "Top 1 Database";
    const IGNORED_VALUES = ["", "?", "-"];
    const SKILL_FIELDS = [
        "Language",
        "Java",
        "JavaScript",
        "Web",
        "PHP",
        ".NET",
        "OS",
        "Cloud",
        "Cloud Tools",
        "Big Data",
        "Mobile*",
        "Network",
        "Databases",
        "Architect",
        "Others",
        "BMP/ERP",
        "CMS",
        "Testing",
        "Testing Tools",
        "Tools",
        "PM Process"
    ];
    const IMPORT_FILE_SEPARATOR = ",";
    const MULTIPICKLIST_SEPARATOR = " |##| ";
    const FIELDS_FOR_CHECKING_DUPLICATES = [
        "Primary Email", "Secondary Email", "Linkedin Profile"
    ];
}
