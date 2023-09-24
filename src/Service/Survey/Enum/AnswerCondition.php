<?php

namespace App\Service\Survey\Enum;

enum AnswerCondition: string
{
    case AnyOf = 'ANY_OF';
    case AllOf = 'ALL_OF';
}
