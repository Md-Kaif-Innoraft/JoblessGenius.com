<?php

namespace App\Repository;

use App\Entity\ExamApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExamApplication>
 *
 * @method ExamApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamApplication[]    findAll()
 * @method ExamApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamApplicationRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ExamApplication::class);
  }
}
