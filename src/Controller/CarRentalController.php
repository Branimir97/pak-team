<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarRentalController extends AbstractController
{
    /**
     * @Route("/najam-vozila", name="car_rental")
     */
    public function index(): Response
    {
        return $this->render('car_rental/index.html.twig');
    }
}
