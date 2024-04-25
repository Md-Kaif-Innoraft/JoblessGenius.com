<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
class ProtectAdminRoutes
{
  /**
   * @var EntityManagerInterface $em
   *  An object of type EntityManagerInterface.
   */
  private $em;
  /**
   * Constructor to inject dependencies.
   *
   * @var EntityManagerInterface $em
   *  Initializes an object of type EntityManagerInterface.
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }
  /**
   * Method to check if user is admin and access routes relating to admin.
   *
   * @var User $user
   *  The User to be validated.
   */
  public function isAdmin(object $user): bool
  {
    // Protecting the admin routes by checking user role.
    if ($user) {
      // Getting the role of user.
      $user = $this->em->getRepository(User::class)->findOneBy(['id' => $user->getId()]);
      $userRole = $user->getUserType();
      // If no user role is defined or the user is not an admin, redirect him.
      if ($userRole == NULL || $userRole !== "admin")
      {
        // return $this->redirectToRoute('app_dashboard');
        return FALSE;
      }
      else
      {
        return TRUE;
      }
    }
    return FALSE;
  }
}
