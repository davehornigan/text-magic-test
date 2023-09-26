<?php

namespace App\Tests;

use App\Service\Survey\Entity\Survey;
use App\Service\Survey\Entity\SurveyAnswer;
use App\Service\Survey\Entity\SurveyQuestion;
use App\Service\Survey\Enum\AnswerCondition;
use App\Service\Survey\Repository\SurveyQuestionAnswerRepository;
use App\Service\Survey\SurveyProcessor;
use Codeception\Attribute\DataProvider;
use Codeception\Example;

class SurveyProcessorCest
{
    #[DataProvider('getAnswers')]
    public function tryToGetResult(FunctionalTester $I, Example $example): void
    {
        $surveyId = $I->haveInRepository(Survey::class, []);
        $questionId = $I->haveInRepository(SurveyQuestion::class, [
            'surveyId' => $surveyId,
            'title' => '2 + 2 = ?',
            'variants' => ['4', '3 + 1', '1 + 1', '5'],
            'correctVariants' => [0, 1],
            'answerCondition' => AnswerCondition::AnyOf
        ]);
        $answerId = $I->haveInRepository(SurveyAnswer::class, [
            'surveyId' => $surveyId,
            'answers' => $answers = [(string)$questionId => $example['selectedVariants']]
        ]);

        /** @var SurveyProcessor $service */
        $service = $I->grabService(SurveyProcessor::class);

        $result = $service->getResult($surveyId, $answerId);

        $I->assertSame($example['expectedResult'], $result->answers[(string)$questionId]->isCorrect);
    }

    protected function getAnswers(): iterable
    {
        yield ['selectedVariants' => [0], 'expectedResult' => true];
        yield ['selectedVariants' => [1], 'expectedResult' => true];
        yield ['selectedVariants' => [0, 1], 'expectedResult' => true];
        yield ['selectedVariants' => [0, 2], 'expectedResult' => false];
        yield ['selectedVariants' => [2, 3], 'expectedResult' => false];
        yield ['selectedVariants' => [3], 'expectedResult' => false];
    }
}
