<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InsurancePoliciesController extends AbstractController
{
    /**
     * @Route("/police-osiguranja", name="insurance_policies")
     */
    public function index(): Response
    {
        return $this->render('insurance_policies/index.html.twig');
    }
}
