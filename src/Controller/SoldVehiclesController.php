<?php

namespace App\Controller;

use App\Repository\SoldVehicleRepository;
use App\Repository\VehicleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoldVehiclesController extends AbstractController
{
    /**
     * @Route("/sold/vehicles", name="sold_vehicles")
     * @param Request $request
     * @param SoldVehicleRepository $soldVehicleRepository
     * @param PaginatorInterface $paginator
     * @param VehicleRepository $vehicleRepository
     * @return Response
     */
    public function index(Request $request, SoldVehicleRepository $soldVehicleRepository,
                          PaginatorInterface $paginator, VehicleRepository $vehicleRepository): Response
    {
        $soldVehicles = $soldVehicleRepository->findAll();
        $page = $request->query->getInt('page', 1);
        $pagination = $paginator->paginate(
            $soldVehicles,
            $page,
            16
        );
        return $this->render('sold_vehicles/index.html.twig', [
            'vehicles'=> $pagination,
        ]);
    }
}
