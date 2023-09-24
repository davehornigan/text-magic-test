<?php

namespace App\Service\Survey\Entity;

use App\Service\Survey\Enum\AnswerCondition;
use App\Service\Survey\Repository\SurveyQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SurveyQuestionRepository::class)]
// #[ORM\HasLifecycleCallbacks] if timestamps must be set directly at the time of writing to the database
class SurveyQuestion
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 240, nullable: false)]
        private string $title,
        #[ORM\Column(type: Types::JSON, nullable: false)]
        private array $variants,
        #[ORM\Column(type: Types::JSON, nullable: false)]
        private array $correctVariants,
        #[ORM\Column(type: Types::STRING, nullable: false, enumType: AnswerCondition::class)]
        private AnswerCondition $answerCondition,
        #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
        private readonly \DateTimeImmutable $createdOn = new \DateTimeImmutable()
    ) {
        $this->id = new NilUuid();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVariants(): array
    {
        return $this->variants;
    }

    public function getCorrectVariants(): array
    {
        return $this->correctVariants;
    }

    public function getAnswerCondition(): AnswerCondition
    {
        return $this->answerCondition;
    }

    public function getCreatedOn(): \DateTimeImmutable
    {
        return $this->createdOn;
    }
}