<?php

declare(strict_types=1);

namespace App\Enum;

abstract class ErrorEnum
{
    public const string ERROR_PASSWORD = 'password_invalid';
    public const string ERROR_PASSWORD_NOT_MATCH = 'password_not_match';

    public const string CONSTRAINT_LENGTH = 'constraint_length';
    public const string CONSTRAINT_LENGTH_EXACT = 'constraint_length_exact';
    public const string CONSTRAINT_NOT_NULL = 'constraint_not_null';
    public const string CONSTRAINT_NOT_BLANK = 'constraint_not_blank';
    public const string CONSTRAINT_INVALID_EMAIL = 'constraint_invalid_email';
    public const string CONSTRAINT_REGEX = 'constraint_regex';
    public const string CONSTRAINT_UNIQUE = 'constraint_unique';
    public const string CONSTRAINT_RANGE_MIN = 'constraint_range_min';
    public const string CONSTRAINT_RANGE_MAX = 'constraint_range_max';
    public const string CONSTRAINT_NOT_IN_RANGE = 'constraint_not_in_range';
    public const string CONSTRAINT_INVALID_PHONE = 'constraint_invalid_phone';

    public const string CODE_INVALID = 'code_invalid';

    public const string FORM_KEY_INVALID = 'form_key_invalid';
    public const string REQUEST_PASSWORD_ALREADY_EXIST = 'request_password_already_exist';
    public const string INVALID_CHOICE = 'invalid_choice';

    public const string SYMPTOMS_INVALID = 'symptoms_invalid';

    public const string MEDIA_TYPE_INVALID = 'media_type_invalid';

    public const string DATE_INVALID = 'date_invalid';

    public const string SURVEY_ALREADY_DONE = 'survey_already_done';
    public const string SURVEY_NOT_COMPLETED = 'survey_not_completed';
    public const string SURVEY_JSON_INVALID = 'survey_json_invalid';
}
