<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderingVehiclesController extends AbstractController
{
    /**
     * @Route("/sustav-narudzbe-vozila", name="ordering_vehicles")
     */
    public function index(): Response
    {
        return $this->render('ordering_vehicles/index.html.twig');
    }
}
