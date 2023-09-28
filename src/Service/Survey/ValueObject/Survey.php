<?php

namespace App\Service\Survey\ValueObject;

class Survey
{
    /** @param array<string, SurveyQuestion> $questions */
    public function __construct(
        public readonly array $questions
    ) {}
}