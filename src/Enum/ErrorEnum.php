<?php

declare(strict_types=1);

namespace App\Enum;

enum ErrorEnum: string
{
    case ERROR_PASSWORD = 'password_invalid';
    case ERROR_PASSWORD_NOT_MATCH = 'password_not_match';

    case CONSTRAINT_LENGTH = 'constraint_length';
    case CONSTRAINT_LENGTH_EXACT = 'constraint_length_exact';
    case CONSTRAINT_NOT_NULL = 'constraint_not_null';
    case CONSTRAINT_NOT_BLANK = 'constraint_not_blank';
    case CONSTRAINT_INVALID_EMAIL = 'constraint_invalid_email';
    case CONSTRAINT_REGEX = 'constraint_regex';
    case CONSTRAINT_UNIQUE = 'constraint_unique';
    case CONSTRAINT_RANGE_MIN = 'constraint_range_min';
    case CONSTRAINT_RANGE_MAX = 'constraint_range_max';
    case CONSTRAINT_NOT_IN_RANGE = 'constraint_not_in_range';
    case CONSTRAINT_INVALID_PHONE = 'constraint_invalid_phone';

    case CODE_INVALID = 'code_invalid';

    case FORM_KEY_INVALID = 'form_key_invalid';
    case REQUEST_PASSWORD_ALREADY_EXIST = 'request_password_already_exist';
    case INVALID_CHOICE = 'invalid_choice';

    case SYMPTOMS_INVALID = 'symptoms_invalid';

    case MEDIA_TYPE_INVALID = 'media_type_invalid';

    case DATE_INVALID = 'date_invalid';

    case SURVEY_ALREADY_DONE = 'survey_already_done';
    case SURVEY_NOT_COMPLETED = 'survey_not_completed';
    case SURVEY_JSON_INVALID = 'survey_json_invalid';
}
