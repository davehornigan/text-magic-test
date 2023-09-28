<?php

namespace App\Service\Survey;

use App\Service\Survey\Repository\SurveyQuestionRepository;
use App\Service\Survey\ValueObject\SurveyQuestion;

class Survey
{
    public function __construct(
        public readonly SurveyQuestionRepository $questionRepository
    ) {}

    public function getSurvey(string $surveyId): ValueObject\Survey
    {
        $questionsData = $this->questionRepository->findBy(['surveyId' => $surveyId]);
        $questions = [];
        foreach ($questionsData as $question) {
            $questionId = (string)$question->getId();
            $questions[$questionId] = new SurveyQuestion(
                (string)$question->getId(),
                $question->getTitle(),
                $question->getVariants(),
                $question->getCorrectVariants(),
                $question->getAnswerCondition()
            );
        }

        return new ValueObject\Survey($questions);
    }
}