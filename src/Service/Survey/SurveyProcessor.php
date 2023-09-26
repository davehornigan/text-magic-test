<?php

namespace App\Service\Survey;

use App\Service\Survey\Enum\AnswerCondition;
use App\Service\Survey\Repository\SurveyQuestionAnswerRepository;
use App\Service\Survey\Repository\SurveyQuestionRepository;
use App\Service\Survey\ValueObject\QuestionAnswerResult;
use App\Service\Survey\ValueObject\SurveyQuestion;
use App\Service\Survey\ValueObject\SurveyResult;

class SurveyProcessor
{
    public function __construct(
        public readonly SurveyQuestionRepository $questionRepository,
        public readonly SurveyQuestionAnswerRepository $answerRepository,
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
        $answersData = $this->answerRepository->find($answerId);
        $answers = [];
        foreach ($answersData->getAnswers() as $questionId => $questionAnswers) {
            $answers[$questionId] = new QuestionAnswerResult(
                $questionId,
                $questionAnswers,
                $this->isCorrectAnswer($questions[$questionId], $questionAnswers)
            );
        }

        return new SurveyResult($surveyId, $answerId, $questions, $answers);
    }

    private function isCorrectAnswer(SurveyQuestion $question, array $selectedVariants): bool
    {
        if ($question->answerCondition === AnswerCondition::AllOf) {
            return $selectedVariants === $question->correctVariants;
        }

        foreach ($selectedVariants as $selectedVariant) {
            if (false === in_array($selectedVariant, $question->correctVariants)) {
                return false;
            }
        }
        return true;
    }
}