<?php

namespace App\Service\Survey;

use App\Service\Survey\Entity\SurveyAnswer;
use App\Service\Survey\Enum\AnswerCondition;
use App\Service\Survey\Repository\SurveyQuestionAnswerRepository;
use App\Service\Survey\ValueObject\QuestionAnswerResult;
use App\Service\Survey\ValueObject\SurveyQuestion;
use App\Service\Survey\ValueObject\SurveyResult;
use Symfony\Component\Uid\Uuid;

class SurveyProcessor
{
    public function __construct(
        public readonly Survey $surveyService,
        public readonly SurveyQuestionAnswerRepository $answerRepository,
    ) {}

    public function fillSurvey(string $surveyId, string $answerId, array $answers): void
    {
        $answersData = $this->answerRepository->find($answerId);
        if ($answersData === null) {
            $answersData = new SurveyAnswer(Uuid::fromString($answerId), Uuid::fromString($surveyId), []);
        }

        $answersData->fillAnswers($answers);
        $this->answerRepository->save($answersData);
    }

    public function getResult(string $surveyId, string $answerId): SurveyResult
    {
        $survey = $this->surveyService->getSurvey($surveyId);
        $questions = $survey->questions;
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