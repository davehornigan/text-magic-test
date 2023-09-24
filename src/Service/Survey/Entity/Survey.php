<?php

namespace App\Service\Survey\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\NilUuid;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: null)]
// #[ORM\HasLifecycleCallbacks] if timestamps must be set directly at the time of writing to the database
class Survey
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    public function __construct(
        #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: false)]
        private readonly \DateTimeImmutable $createdOn = new \DateTimeImmutable()
    ) {
        $this->id = new NilUuid();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getCreatedOn(): \DateTimeImmutable
    {
        return $this->createdOn;
    }
}