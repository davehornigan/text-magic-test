<?php

namespace App\Service\Survey\Entity;

use App\Service\Survey\Repository\SurveyAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SurveyAnswerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SurveyAnswer
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    public function __construct(
        #[ORM\Column(type: Types::JSON, nullable: false)]
        private array $answers,
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        private readonly \DateTimeImmutable $createdOn = new \DateTimeImmutable(),
        #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
        private \DateTimeInterface $updatedOn = new \DateTime()
    ) {
        $this->id = new NilUuid();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getAnswers(): array
    {
        return $this->answers;
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