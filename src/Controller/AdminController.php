<?php

namespace App\Controller;

use App\Entity\Exam;
use App\Form\ExamType;
use App\Entity\ExamResult;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class Admin Controller for routing related to admin.
 */
class AdminController extends AbstractController
{

  /**
   * @var object $em
   *  Entity manager interface.
   */
  private $em;

  /**
   * Constructor to initialize instace of class.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * Function index for routing to index page of admin.
   *
   * @return Response
   *  return respose to render admin index page.
   */
  #[Route('/admin', name: 'app_admin')]
  public function index(): Response
  {
    return $this->render('admin/index.html.twig');
  }

  /**
   * Function createExam for creating exam and routing to create page of admin.
   *
   * @return Response
   *  return respose to render create Exam page.
   */
  #[Route('/createExam', name: 'app_createExam')]

  public function createExam(Request $request): Response
  {
    // Get owner name of exam.
    $owner = $this->getUser()->getOwner();
    // Create a new object of Exam.
    $Exam = new Exam();
    // Set owner name in Exam.
    $Exam->setOwner($owner);
    // Create a view of exam form.
    $form = $this->createForm(ExamType::class, $Exam);
    // Handle request.
    $form->handleRequest($request);
    // Checking if the form is submitted or not.
    if ($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($Exam);
      $this->em->flush();
      $this->addFlash('message', 'Exam Created Successfully');
      return $this->redirectToRoute('app_admin');
    }
    return $this->render('admin/createExam.html.twig', [
      'form' => $form->createview()
    ]);
  }

  /**
   * Function createdExam for createdExam and routing to created page of admin.
   *
   * @return Response
   *  return respose to render created Exam page.
   */
  #[Route('/createdExam', name: 'app_createdExam')]
  public function createdExam(): Response
  {
    // Query buider to get result where owner is required owner.
    $exams = $this->em->getRepository(Exam::class)->createQueryBuilder('e')
      ->where('e.owner = :owner')
      ->setParameter('owner', 'innoraft')
      ->getQuery()
      ->getResult();
    // Render Created Exam page.
    return $this->render('admin/createdExam.html.twig',[
      'exams' => $exams
    ]);
  }

  /**
   * Function deleteExam for deleting exam and routing to delete exam page of admin.
   *
   * @param int $examid
   *
   * @return Response
   *  return respose to render admin home page.
   */
  #[Route('/deleteExam/{examid}', name: 'app_deleteExam')]
  public function deleteExam(int $examid): Response
  {
    // Getting exams where exam id is required id.
    $exam = $this->em->getRepository(Exam::class)->find($examid);
    $this->em->remove($exam);
    $this->em->flush();
    $this->addFlash('message', 'Exam Deleted Successfully');
    // Redirecting to home page of admin.
    return $this->redirectToRoute('app_admin');
  }

  /**
   * Function adminResult for Result and routing to result page of admin.
   *
   * @return Response
   *  return respose to render result page.
   */
  #[Route('/adminResult', name: 'app_adminResult')]
  public function result(Request $request): Response
  {
    // Getting owner of exam.
    $owner = $this->getUser()->getOwner();
    // Getting all the result from database.
    $allResults = $this->em->getRepository(ExamResult::class)->findAll();
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
    // Render admin result page.
    return $this->render('admin/result.html.twig',
      [
        'studentNames' => $studentNames,
        'collegeNames' => $collegeNames,
        'results' => $results,
        'examNames' => $examNames,
        'totalMarks' => $totalMarks
      ]
    );
  }

}
