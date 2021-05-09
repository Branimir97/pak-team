<?php

namespace App\Controller;

use App\Entity\Cover;
use App\Entity\Image;
use App\Entity\SoldVehicle;
use App\Entity\Vehicle;
use App\Form\InsertSoldVehiclesType;
use App\Form\VehicleType;
use App\Repository\CoverRepository;
use App\Repository\ImageRepository;
use App\Repository\SoldVehicleRepository;
use App\Repository\VehicleRepository;
use App\Service\UploaderHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/admin-panel")
 */
class AdminPanelController extends AbstractController
{
    /**
     * @Route("/", name="vehicle_index", methods={"GET", "POST"})
     * @param Request $request
     * @param VehicleRepository $vehicleRepository
     * @return JsonResponse
     */
    public function index(Request $request, VehicleRepository $vehicleRepository): Response
    {
        if($request->isXmlHttpRequest()) {
            $filter = $request->get('filterBy');
            $vehicles = $vehicleRepository->findByFilter($filter);
            return new JsonResponse($vehicles);
        }
        return
            $this->render('admin_panel/sold_vehicles_list.html.twig', [
            'vehicles' => $vehicleRepository->findAllAsArray(),
        ]);
    }

    /**
     * @Route("/dodaj-novo-vozilo", name="vehicle_new", methods={"GET","POST"})
     * @param Request $request
     * @param UploaderHelper $uploaderHelper
     * @return Response
     */
    public function new(Request $request, UploaderHelper $uploaderHelper): Response
    {
        $vehicle = new Vehicle();
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $uploadedFile */
            $uploadedFiles = $form->get('imageFile')->getData();
                $coverFile = $form->get('coverFile')->getData();
            foreach ($uploadedFiles as $uploadedFile)
            {
                $newFileName = $uploaderHelper->uploadVehicleImage($uploadedFile);
                if(is_null($newFileName)) {
                    $this->addFlash('warning',
                        'Pogreška prilikom prijenosa jedne od ostalih fotografija. 
                        Dopušteni formati fotografija: jpg, jpeg i png.
                        Vratite se na prethodni zaslon.');

                    return $this->redirectToRoute('vehicle_new');
                }
                $image = new Image();
                $image->setImagePath($newFileName);
                $image->setVehicle($vehicle);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($image);
            }
            $newCoverFileName = $uploaderHelper->uploadCoverImage($coverFile);
            if(is_null($newCoverFileName)) {
                $this->addFlash('warning',
                    'Pogreška prilikom spremanja cover fotografije. 
                    Dopušteni formati fotografija: jpg, jpeg i png.
                             Vratite se na prethodni zaslon.');
                return $this->redirectToRoute('vehicle_new');
            }
            $cover = new Cover();
            $cover->setVehicle($vehicle);
            $cover->setImagePath($newCoverFileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cover);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Novo vozilo uspješno dodano.');
            return $this->redirectToRoute('vehicle_index');
        }
        return $this->render('admin_panel/new_vehicle.html.twig', [
            'admin_panel' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vozilo/{id}", name="vehicle_show", methods={"GET"})
     * @param Vehicle $vehicle
     * @return Response
     */
    public function show(Vehicle $vehicle): Response
    {
        return $this->render('admin_panel/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * @Route("/vozilo/{id}/uredi", name="vehicle_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Vehicle $vehicle
     * @return Response
     */
    public function edit(Request $request, Vehicle $vehicle): Response
    {
        $form = $this->createForm(VehicleType::class, $vehicle, ['required'=>false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Podaci o vozilu uspješno su ažurirani.');
            return $this->redirectToRoute('vehicle_index');
        }

        return $this->render('admin_panel/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vozilo/{id}/obrisi", name="vehicle_delete", methods={"DELETE"})
     * @param Request $request
     * @param Vehicle $vehicle
     * @param VehicleRepository $vehicleRepository
     * @param ImageRepository $imageRepository
     * @param CoverRepository $coverRepository
     * @return Response
     */
    public function delete(Request $request, Vehicle $vehicle, VehicleRepository $vehicleRepository, ImageRepository $imageRepository, CoverRepository $coverRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->request->get('_token'))) {
            $vehicle = $vehicleRepository->findOneBy(['id'=>$request->get('id')]);
            $images = $imageRepository->findBy(['vehicle'=>$vehicle]);
            if($images !== null) {
                foreach($images as $image) {
                    unlink('../public/uploads/'.$image->getImagePath());
                }
            }
            $cover = $coverRepository->findOneBy(['vehicle'=>$vehicle]);
            if($cover !== null) {
                unlink('../public/uploads/covers/'.$cover->getImagePath());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }
        return $this->redirectToRoute('vehicle_index');
    }

    /**
     * @Route("/{id}/vidljivost", name="vehicle_change_visibility")
     * @param Vehicle $vehicle
     * @return RedirectResponse
     */
    public function changeVisibility(Vehicle $vehicle): RedirectResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($vehicle->getVisibility())
        {
            $vehicle->setVisibility(0);
        } else
        {
            $vehicle->setVisibility(1);
        }
        $entityManager->persist($vehicle);
        $entityManager->flush();
        return $this->redirectToRoute('vehicle_index');
    }

    /**
     * @Route("/dodaj-novo-prodano-vozilo", name="insert_sold_vehicles")
     * @param Request $request
     * @param UploaderHelper $uploaderHelper
     * @return RedirectResponse|Response
     */
    public function insertSoldVehiclesAction(Request $request, UploaderHelper $uploaderHelper) {
        $form = $this->createForm(InsertSoldVehiclesType::class);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFiles = $form->get('imageFile')->getData();
            foreach ($uploadedFiles as $uploadedFile)
            {
                $soldVehicle = new SoldVehicle();
                $newFileName = $uploaderHelper->uploadSoldVehicleImage($uploadedFile);
                $explodedTitle = explode('.', $newFileName);
                $soldVehicle->setTitle($explodedTitle[0]);
                $soldVehicle->setImagePath($newFileName);
                $entityManager->persist($soldVehicle);
                $entityManager->flush();

            }
            $this->addFlash('success', 'Prodana vozila uspješno dodana.');
            return $this->redirectToRoute('vehicle_index');
        }
        return $this->render('admin_panel/new_sold_vehicles.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/prodana-vozila", name="sold_vehicle_index", methods={"GET"})
     */
    public function listSoldVehicles(SoldVehicleRepository $soldVehicleRepository): Response
    {
        return $this->render('admin_panel/sold_vehicles_list.html.twig', [
            'sold_vehicles' => $soldVehicleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/prodano-vozilo/{id}/obrisi", name="sold_vehicle_delete", methods={"DELETE"})
     */
    public function deleteSoldVehicle(Request $request, SoldVehicle $soldVehicle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$soldVehicle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($soldVehicle);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Prodano vozilo uspješno je obrisano.');
        return $this->redirectToRoute('vehicle_index');
    }
}
