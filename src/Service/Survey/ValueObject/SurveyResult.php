<?php

namespace App\Service\Survey\ValueObject;

class SurveyResult
{
    /**
     * @param string $surveyId
     * @param string $answerId
     * @param array<string, SurveyQuestion> $questions
     * @param array<string, QuestionAnswerResult> $answers
     */
    public function __construct(
        public readonly string $surveyId,
        public readonly string $answerId,
        public readonly array $questions,
        public readonly array $answers
    ) {}
}