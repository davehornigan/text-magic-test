<?php

namespace App\Service\Survey\Entity;

use App\Service\Survey\Repository\SurveyQuestionAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SurveyQuestionAnswerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SurveyAnswer
{

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        private Uuid $id,
        #[ORM\Column(type: UuidType::NAME, nullable: false)]
        private Uuid $surveyId,
        #[ORM\Column(type: Types::JSON, nullable: false)]
        private array $answers,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        private \DateTimeImmutable $createdOn = new \DateTimeImmutable(),
        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
        private \DateTimeInterface $updatedOn = new \DateTime()
    ) {}

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSurveyId(): Uuid
    {
        return $this->surveyId;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function fillAnswers(array $answers): static
    {
        $this->answers = $answers;

        return $this;
    }

    public function getCreatedOn(): \DateTimeImmutable
    {
        return $this->createdOn;
    }

    public function getUpdatedOn(): \DateTimeInterface
    {
        return $this->updatedOn;
    }

    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        $this->updatedOn = new \DateTime();
    }
}