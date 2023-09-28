<?php

namespace App\DataFixtures;

use App\Service\Survey\Entity\Survey;
use App\Service\Survey\Entity\SurveyQuestion;
use App\Service\Survey\Enum\AnswerCondition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $survey = new Survey();
        $manager->persist($survey);
        foreach ($this->getQuestions() as $questionData) {
            $question = new SurveyQuestion(
                $survey->getId(),
                $questionData['title'],
                $questionData['variants'],
                $questionData['correctVariants'],
                AnswerCondition::AnyOf
            );
            $manager->persist($question);
        }

        $manager->flush();
    }

    private function getQuestions(): array
    {
        return [
            [
                'title' => '1 + 1 = ?',
                'variants' => [
                    'item1' => '3',
                    'item2' => '2',
                    'item3' => '0',
                ],
                'correctVariants' => ['item2']
            ],
            [
                'title' => '2 + 2 = ?',
                'variants' => [
                    'item1' => '4',
                    'item2' => '3 + 1',
                    'item3' => '10',
                ],
                'correctVariants' => ['item1', 'item2']
            ],
            [
                'title' => '3 + 3 = ?',
                'variants' => [
                    'item1' => '1 + 5',
                    'item2' => '1',
                    'item3' => '6',
                    'item4' => '2 + 4',
                ],
                'correctVariants' => ['item1', 'item3', 'item4']
            ],
            [
                'title' => '4 + 4 = ?',
                'variants' => [
                    'item1' => '8',
                    'item2' => '4',
                    'item3' => '0',
                    'item4' => '0 + 8',
                ],
                'correctVariants' => ['item1', 'item4']
            ],
            [
                'title' => '5 + 5 = ?',
                'variants' => [
                    'item1' => '6',
                    'item2' => '18',
                    'item3' => '10',
                    'item4' => '9',
                ],
                'correctVariants' => ['item3']
            ],
            [
                'title' => '6 + 6 = ?',
                'variants' => [
                    'item1' => '3',
                    'item2' => '9',
                    'item3' => '0',
                    'item4' => '5 + 7',
                ],
                'correctVariants' => ['item4', 'item5']
            ],
            [
                'title' => '7 + 7 = ?',
                'variants' => [
                    'item1' => '5',
                    'item2' => '14',
                ],
                'correctVariants' => ['item2', 'item5']
            ],
            [
                'title' => '8 + 8 = ?',
                'variants' => [
                    'item1' => '16',
                    'item2' => '12',
                    'item3' => '9',
                    'item4' => '5',
                ],
                'correctVariants' => ['item1']
            ],
            [
                'title' => '9 + 9 = ?',
                'variants' => [
                    'item1' => '18',
                    'item2' => '9',
                    'item3' => '17 + 1',
                    'item4' => '2 + 16',
                ],
                'correctVariants' => ['item1', 'item3', 'item4']
            ],
            [
                'title' => '10 + 10 = ?',
                'variants' => [
                    'item1' => '0',
                    'item2' => '2',
                    'item3' => '8',
                    'item4' => '20',
                ],
                'correctVariants' => ['item4']
            ]
        ];
    }
}
