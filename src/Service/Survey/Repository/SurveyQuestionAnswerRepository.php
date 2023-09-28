<?php

namespace App\Service\Survey\Repository;

use App\Service\Survey\Entity\SurveyAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @template-extends  EntityRepository<SurveyAnswer> */
class SurveyQuestionAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SurveyAnswer::class);
    }

    public function save(SurveyAnswer $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->refresh($entity);
    }
}