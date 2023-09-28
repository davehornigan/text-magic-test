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
use Symfony\Component\Uid\Uuid;

class SurveyProcessorCest
{
    #[DataProvider('getAnswers')]
    public function tryToGetResult(FunctionalTester $I, Example $example): void
    {
        $surveyId = $I->haveInRepository(Survey::class, []);
        $questionId = $I->haveInRepository(SurveyQuestion::class, [
            'surveyId' => $surveyId,
            'title' => '2 + 2 = ?',
            'variants' => [
                'item1' => '4',
                'item2' => '3 + 1',
                'item3' => '1 + 1',
                'item4' => '5'
            ],
            'correctVariants' => ['item1', 'item2'],
            'answerCondition' => AnswerCondition::AnyOf
        ]);
        $answerId = $I->haveInRepository(SurveyAnswer::class, [
            'id' => Uuid::v4(),
            'surveyId' => $surveyId,
            'answers' => [(string)$questionId => $example['selectedVariants']]
        ]);

        /** @var SurveyProcessor $service */
        $service = $I->grabService(SurveyProcessor::class);

        $result = $service->getResult($surveyId, $answerId);

        $I->assertSame($example['expectedResult'], $result->answers[(string)$questionId]->isCorrect);
    }

    protected function getAnswers(): iterable
    {
        yield ['selectedVariants' => ['item1'], 'expectedResult' => true];
        yield ['selectedVariants' => ['item2'], 'expectedResult' => true];
        yield ['selectedVariants' => ['item1', 'item2'], 'expectedResult' => true];
        yield ['selectedVariants' => ['item1', 'item3'], 'expectedResult' => false];
        yield ['selectedVariants' => ['item3', 'item4'], 'expectedResult' => false];
        yield ['selectedVariants' => ['item4'], 'expectedResult' => false];
    }
}
