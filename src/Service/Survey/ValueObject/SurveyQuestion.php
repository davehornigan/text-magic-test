<?php

namespace App\Service\Survey\ValueObject;

use App\Service\Survey\Enum\AnswerCondition;

class SurveyQuestion
{
    public function __construct(
        public readonly string $questionId,
        public readonly string $questionTitle,
        public readonly array $variants,
        public readonly array $correctVariants,
        public readonly AnswerCondition $answerCondition = AnswerCondition::AnyOf
    ) {}
}