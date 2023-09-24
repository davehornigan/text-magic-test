<?php

namespace App\Service\Survey\ValueObject;

class QuestionAnswerResult
{
    public function __construct(
        public readonly string $questionId,
        public readonly array $answers,
        public readonly bool $isCorrect,
    ) {}
}