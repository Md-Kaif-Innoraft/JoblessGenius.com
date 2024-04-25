<?php

namespace App\Service;

use App\Entity\ExamResult;
use Doctrine\ORM\EntityManagerInterface;

class AdminService {

  /**
   * @var EntityManagerInterface $em
   *  EntityManagerInterface to use in repository.
   */
  private $em;
  private $adminService;
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->em = $entityManager;
  }

  /**
   * Method to get All Result.
   *
   * @return array
   *  Return array of all the results.
   */
  public function getAllResult(): Array
  {
    $allResults = $this->em->getRepository(ExamResult::class)->findAll();
    return $allResults;
  }

  /**
   * Method getResultAdmin for Result of admin.
   *
   * @return array
   *  return array containing all the exam names owner ane marks.
   */
  public function getResultAdmin(string $owner): Array
  {
    // Getting all the result from database.
    $allResults = $this->getAllResult();
    // Initializing empty arrays to store results.
    $studentNames = [];
    $collegeNames = [];
    $results = [];
    $totalMarks = [];
    $examNames = [];
    // For loop to traverse all results.
    for ($i = 0; $i < count($allResults); $i++)
    {
      // Check if required result belongs to the required owner.
      if ($allResults[$i]->getExamId()->getOwner() == $owner)
      {
        // Storing students name.
        $studentNames[] = $allResults[$i]->getUserId()->getProfile()->getName();
        // Storing college names.
        $collegeNames[] = $allResults[$i]->getUserId()->getProfile()->getCollegeName();
        // Storing results of students.
        $results[] = $allResults[$i]->getResult();
        // Storing exam names.
        $examNames[] = $allResults[$i]->getExamId()->getTitle();
        // Stroing total marks.
        $totalMarks[] = $allResults[$i]->getExamId()->getTotalMarks();
      }
    }
    return [$studentNames, $collegeNames, $results, $examNames, $totalMarks];
  }
}
