<?php

namespace App\Service\Survey;

use App\Service\Survey\Repository\SurveyAnswerRepository;
use App\Service\Survey\Repository\SurveyQuestionRepository;
use App\Service\Survey\ValueObject\QuestionAnswerResult;
use App\Service\Survey\ValueObject\SurveyQuestion;
use App\Service\Survey\ValueObject\SurveyResult;

class SurveyProcessor
{
    public function __construct(
        public readonly SurveyQuestionRepository $questionRepository,
        public readonly SurveyAnswerRepository $answerRepository,
    ) {}

    public function getResult(string $surveyId, string $answerId): SurveyResult
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
        $answersData = $this->answerRepository->findBy(['surveyId' => $surveyId, 'answerId' => $answerId]);
        $answers = [];
        foreach ($answersData as $answer) {
            $questionId = $answer->getQuestionId();
            $answers[$questionId] = new QuestionAnswerResult(
                $questionId,
                $answer->getSelectedVariants(),
                $this->isCorrectAnswer($questions[$questionId], $answer->getSelectedVariants())
            );
        }

        return new SurveyResult($surveyId, $answerId, $questions, $answers);
    }

    private function isCorrectAnswer(SurveyQuestion $question, array $selectedVariants): bool
    {
        return false;
    }
}