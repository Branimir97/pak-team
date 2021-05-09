<?php

namespace App\Controller;

use App\Entity\SoldVehicle;
use App\Repository\SoldVehicleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/prodana-vozila")
 */
class SoldVehiclesController extends AbstractController
{
    /**
     * @Route("/", name="sold_vehicles")
     * @param Request $request
     * @param SoldVehicleRepository $soldVehicleRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request,
                          SoldVehicleRepository $soldVehicleRepository,
                          PaginatorInterface $paginator): Response
    {
        $soldVehicles = $soldVehicleRepository->findBy([], ['id'=>'DESC']);
        $page = $request->query->getInt('stranica', 1);
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
