<?php

namespace App\Controller;

use App\Entity\Inquirie;
use App\Entity\Vehicle;
use App\Form\InquirieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InquirieController extends AbstractController
{
    /**
     * @Route("/{id}/stvori", name="inquirie_create", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $inquirie = new Inquirie();
        $form = $this->createForm(InquirieType::class, $inquirie);
        $form->handleRequest($request);
        $vehicle_id = $request->get('id');

        /** @var Vehicle $vehicle */
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($vehicle_id);
        $vehicle_title = $vehicle->getMark().' '.$vehicle->getModel();
        if($form->isSubmitted() && $form->isValid())
        {
            $inquirie->setVehicle($vehicle);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inquirie);
            $entityManager->flush();

            $this->addFlash('success', 'Uspješno ste poslali upit za '.$vehicle_title.'. Očekujte naš odgovor putem email-a uskoro!');
            return $this->redirectToRoute('vehicle_details', ['id'=> $vehicle->getId()]);
        }
        return $this->render('admin_panel/inquirie_list.html.twig', [
            'form' => $form->createView(),
            'vehicle_title' => $vehicle_title
        ]);
    }
}
