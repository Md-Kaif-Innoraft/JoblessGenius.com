<?php

namespace App\Service;

use App\Entity\Exam;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\ExamResult;
use App\Entity\ExamApplication;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MyServices to handle data related to controllers.
 */
class MyService {

  /**
   * @var EntityManagerInterface $em
   *  EntityManagerInterface to use in repository.
   */
  private $em;

  /**
   * Constructor to initialize instace of class.
   *
   * @param EntityManagerInterface $entityManager
   *  EntityManagerInterface to use in repository.
   */
  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->em = $entityManager;
  }

  /**
   * Method to Set Exam id and User id in ExamApplication.
   *
   * @param int $id
   *  Take user id of user.
   * @param int $examid
   *  Take exam id of exam in which user is applied.
   * @param object $ExamApplication
   *  Take object of ExamApplication to set data.
   */
  public function setExamApplicationData(int $id, int $examid, object $ExamApplication) {

    // Find user data from database.
    $user = $this->em->getRepository(User::class)->find($id);
    // Find exam data from database.
    $exam = $this->em->getRepository(Exam::class)->find($examid);

    // Set Exam id in Exam Application.
    $ExamApplication->setExamId($exam);
    // Set user id in Exam Application.
    $ExamApplication->setUserId($user);
  }

  /**
   * Function setDataBase to Persist the data to the database.
   *
   * @param object $data.
   *  Data as object to set in database.
   */
  public function setDataBase(object $data)
  {
    $this->em->persist($data);
    $this->em->flush();
  }

  /**
   * Function getUpcomingExam data of upcomingexam of user.
   *
   * @return array
   *  return array upcomingexam data.
   */
  public function getUpcomingExam(): Array
  {
    // Data of all Exams.
    $allExams = $this->em->getRepository(Exam::class)->findAll();
    // Data of all applied exams.
    $appliedExams = $this->em->getRepository(ExamApplication::class)->findAll();
    // Array to store data of upcoming exam.
    $upcomingExams = [];

    // Create an array of applied exam IDs
    $appliedExamIds = [];
    // For loop to traverse and get id of applied exams.
    foreach ($appliedExams as $appliedExam) {
      $appliedExamIds[] = $appliedExam->getExamId()->getId();
    }

    // Check each exam in $allExams and add to $upcomingExams if not applied
    foreach ($allExams as $exam) {
      if (!in_array($exam->getId(), $appliedExamIds))
      {
        $upcomingExams[] = $exam;
      }
    }
    return $upcomingExams;
  }

  /**
   * Function calculateMarks data of Appered exam of user.
   *
   * @return int
   *  return marks of user.
   */
  public function calculateMarks(array $answers): int
  {
    $totalMarks = 0;
    if ($answers != null)
    {
      // For loop to loop through every question and correct answer.
      foreach ($answers as $questionId => $selectedOption)
      {
        $question = $this->em->getRepository(Question::class)->find($questionId);
        // If the answer is correct increase marks.
        if ($question && $selectedOption === $question->getCorrectAns())
        {
          $totalMarks++;
        }
      }
    }
    return $totalMarks;
  }

  /**
   * Function appliedExam of user.
   *
   * @param int $id
   *  Take id of user.
   *
   * @return array
   *  return data of appliedExam.
   */
  public function appliedExam(int $id): Array
  {
    // Data of all applied exams.
    $appliedExam = $this->em->getRepository(ExamApplication::class)->findAll();
    // Array to store exam names.
    $examsName = [];
    // Array to store exam status.
    $status = [];
    // Array to store owner names.
    $owner = [];
    // Array to store duration of exam.
    $duration = [];
    // Array to store exam ids.
    $examId = [];
    for ($i=0; $i<count($appliedExam); $i++)
    {
      if ($id == $appliedExam[$i]->getUserId()->getId())
      {
        $examsName[] = $appliedExam[$i]->getExamId()->getTitle();
        $examId[] = $appliedExam[$i]->getExamId()->getId();
        $status[] = $appliedExam[$i]->getExamId()->getStatus();
        $owner[] = $appliedExam[$i]->getExamId()->getOwner();
        $duration[] = $appliedExam[$i]->getExamId()->getDuration();
      }
    }
    return [$examsName, $status, $owner, $duration, $examId];
  }

  /**
   * Function getResult of user.
   *
   * @param int $id
   *  Take id of user.
   *
   * @return array
   *  return data of result of user.
   */
  public function getResult (int $id): array
  {
    // Arrray to store result.
    $results = [];
    // Arrray to store names.
    $names =[] ;
    // Arrray to store owner.
    $owners = [];
    // Arrray to store total marks.
    $totalMarks = [];
    // Data of all the results.
    $allResults = $this->em->getRepository(ExamResult::class)->findAll();
    for($i=0; $i<count($allResults); $i++)
    {
      if ($allResults[$i]->getUserId()->getId() == $id)
      {
        $results[] = $allResults[$i]->getResult();
        $names[] = $allResults[$i]->getExamId()->getTitle();
        $owners[] = $allResults[$i]->getExamId()->getOwner();
        $totalMarks[] = $allResults[$i]->getExamId()->getTotalMarks();
      }
    }
    return [$results, $names, $owners, $totalMarks];
  }

}
