<?php

namespace App\Tests;

use App\Service\Survey\Entity\SurveyQuestion;
use App\Service\Survey\Enum\AnswerCondition;
use App\Service\Survey\Repository\SurveyQuestionRepository;
use App\Service\Survey\Survey;
use Symfony\Component\Uid\Uuid;

class SurveyTest extends \Codeception\Test\Unit
{

    public function testGetSurvey(): void
    {
        $surveyId = Uuid::v4();

        $questions = [
            new SurveyQuestion(
                $surveyId,
                'Some question 1',
                ['item1' => 'Variant 1', 'item2' => 'Variant 2'],
                ['item1'],
                AnswerCondition::AnyOf
            ),
        ];

        $repo = $this->createMock(SurveyQuestionRepository::class);
        $repo->method('findBy')->willReturn($questions);
        $service = new Survey($repo);
        $survey = $service->getSurvey('someId');

        self::assertCount(count($questions), $survey->questions);
        $firstQuestionId = array_key_first($survey->questions);
        self::assertSame($questions[0]->getTitle(), $survey->questions[$firstQuestionId]->questionTitle);
        self::assertSame($questions[0]->getVariants(), $survey->questions[$firstQuestionId]->variants);
        self::assertSame($questions[0]->getCorrectVariants(), $survey->questions[$firstQuestionId]->correctVariants);
    }
}
