<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class MainController for routing to main page.
 */
class MainController extends AbstractController
{

  /**
   * Function index for routing to main page.
   *
   * @return Response
   *  return respose to render index page.
   */
  #[Route('/', name: 'app_main')]
  public function index(): Response
  {
    return $this->render('main/index.html.twig');
  }
}
