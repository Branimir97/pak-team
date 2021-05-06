<?php

namespace App\Controller;

use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IncomingVehiclesController extends AbstractController
{
    /**
     * @Route("/vozila-u-dolasku", name="incoming_vehicles")
     * @param VehicleRepository $vehicleRepository
     * @return Response
     */
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $vehicles = $vehicleRepository->findBy(['status'=>'U dolasku', 'visibility'=>true], ['id'=>'DESC']);
        return $this->render('incoming_vehicles/index.html.twig', [
            'vehicles' => $vehicles
        ]);
    }
}
