<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController for routing related to login.
 */
class SecurityController extends AbstractController
{

  /**
   * Function login for routing to login page of user.
   *
   * @param AuthenticationUtils $authenticationUtils
   *  Request from url.
   *
   * @return Response
   *  return respose to render login page.
   */
  #[Route(path: '/login', name: 'app_login')]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    // Check if user is login.
    if ($this->getUser())
    {
      // Get user type of user.
      $user = $this->getUser()->getUserType();
      // Check if user is admin redirect to admin dashboard.
      if ($user == "admin")
      {
        return $this->redirectToRoute('app_admin');
      }
      // Else redirect to user dashboard.
      else
      {
        return $this->redirectToRoute('app_dashboard');
      }
    }
    // Get the login error if there is one.
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user.
    $lastUsername = $authenticationUtils->getLastUsername();
    // Render login page.
    return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
  }

  /**
   * Function logout for routing to logout page of user.
   *
   * @return void
   *  return nothing.
   */
  #[Route(path: '/logout', name: 'app_logout')]
  public function logout(): void
  {
    throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }
}
