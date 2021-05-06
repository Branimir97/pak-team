<?php

namespace App\Controller;

use App\Entity\Inquirie;
use App\Entity\Vehicle;
use App\Form\InquirieType;
use App\Repository\InquirieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/inquirie")
 * @IsGranted("ROLE_ADMIN")
 */
class InquirieController extends AbstractController
{

    /**
     * @Route("/{id}/create", name="inquirie_create", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
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
        return $this->render('inquirie/index.html.twig', [
            'form' => $form->createView(),
            'vehicle_title' => $vehicle_title
        ]);
    }

    /**
     * @Route("/", name="inquirie_list", methods={"GET"})
     * @param InquirieRepository $inquirieRepository
     * @return Response
     */
    public function show(InquirieRepository $inquirieRepository): Response
    {
        $inquiries = $inquirieRepository->findBy([], ['id'=>'DESC']);

        return $this->render('inquirie/index.html.twig', [
            'inquiries' => $inquiries,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="inquirie_delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteInquirie(Request $request)
    {
        $inquirie_id = $request->get('id');
        $inquirie = $this->getDoctrine()->getRepository(Inquirie::class)->find($inquirie_id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($inquirie);
        $entityManager->flush();

        $this->addFlash('success', "Upit uspješno izbrisan!");
        return $this->redirectToRoute('inquirie_list');
    }
}
