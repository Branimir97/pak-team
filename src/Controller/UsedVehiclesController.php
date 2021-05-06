<?php

namespace App\Controller;

use App\Entity\Cover;
use App\Entity\Inquirie;
use App\Entity\Vehicle;
use App\Form\InquirieType;
use App\Repository\ImageRepository;
use App\Repository\VehicleRepository;
use App\Service\APIService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsedVehiclesController extends AbstractController
{
    /**
     * @Route("/", name="used_vehicles")
     * @param Request $request
     * @param VehicleRepository $vehicleRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, VehicleRepository $vehicleRepository, PaginatorInterface $paginator): Response
    {
        $vehicles = $vehicleRepository->getQueryBuilder();
        $limit = 12;
        $page = $request->query->getInt('stranica', 1);
        $pagination = $paginator->paginate($vehicles, $page, $limit);
        if($page*$limit >= $vehicleRepository->getMaxRes()){
            $results_span = ($page*$limit) - $limit + 1 .' do '.$vehicleRepository->getMaxRes();
        } else {
            $results_span = ($page*$limit) - $limit + 1 .' do '. $page*$limit;
        }
        return $this->render('used_vehicles/index.html.twig', [
            'vehicles' => $pagination,
            'results_span' => $results_span,
            'max' => $vehicles->getMaxResults()
        ]);
    }

    /**
     * @Route("/vozilo/{id}/detalji", name="vehicle_details")
     * @param Request $request
     * @param APIService $APIService
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public function details(Request $request, ImageRepository $imageRepository): Response
    {
        $vehicle_id = $request->get('id');
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($vehicle_id);

        $inquirie = new Inquirie();
        $form = $this->createForm(InquirieType::class, $inquirie);
        $form->handleRequest($request);
        $vehicle_id = $request->get('id');

        $cover = $this->getDoctrine()->getRepository(Cover::class)->findBy([
            'vehicle'=>$vehicle
        ]);

        /** @var Vehicle $vehicle */
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($vehicle_id);
        $vehicle_title = $vehicle->getMark().' '.$vehicle->getModel();
        if($form->isSubmitted() && $form->isValid())
        {
            $inquirie->setVehicle($vehicle);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inquirie);
            $entityManager->flush();

            $this->addFlash('success', 'Uspješno ste poslali upit za vozilo '.$vehicle_title.'. Očekujte naš odgovor uskoro!');
            return $this->redirectToRoute('vehicle_details', ['id'=> $vehicle->getId()]);
        }

        $imagesNumber = $imageRepository->getImagesNumber($vehicle);
        return $this->render('vehicle/details.html.twig', [
            'cover' => $cover,
            'vehicle' => $vehicle,
            'form' => $form->createView(),
            'imagesNumber' => $imagesNumber
        ]);
    }
}
