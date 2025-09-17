<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Criterion;
use App\Entity\Descriptor;
use App\Form\CriterionFormType;
// Entities
use App\Repository\CriterionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Repositories
use Symfony\Component\HttpFoundation\Request;

// Forms

class CriterionController extends AbstractController
{
    #[Route(path: '/criterion', name: 'criterion')]
    public function index(CriterionRepository $criterionRepository): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // Generate criteria buttons
        $criteria = $criterionRepository->findAll();

        return $this->render('criterion/index.html.twig', [
            'criteria' => $criteria,
            'error' => false,
        ]);
    }

    #[Route(path: '/criterion/new', name: 'new-criterion')]
    public function new(Request $request)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $form = $this->createForm(CriterionFormType::class);
        $form->handleRequest($request);
        // dump($formTopic);

        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // New Criteria
            $criterion = new Criterion();
            $criterion->setName($data->getName());

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($criterion);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');

            // Redirect to add descriptor
            return $this->redirectToRoute('new-descriptor', ['criterionId' => $criterion->getId()]);
        }

        return $this->render('criterion/new.html.twig', [
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/criterion/edit/{id}', name: 'edit-criterion')]
    public function edit(int $id, Request $request, CriterionRepository $criterionRepository)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $criterion = $criterionRepository->find($id);
        if (!$criterion) {
            throw $this->createNotFoundException('No Criteria found for id '.$id);
        }

        $form = $this->createForm(CriterionFormType::class, $criterion);
        $form->handleRequest($request);
        // dump($formSubject);

        // Process Form form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dump($newSubject);

            $criterion->setName($data->getName());

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($criterion);
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to add descriptor
            return $this->redirectToRoute('criterion');
        }

        return $this->render('criterion/edit.html.twig', [
            'criterion' => $criterion,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/criterion/add', name: 'add-criterion')]
    public function add(Request $request)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $form = $this->createForm(AddCriterionFormType::class);
        $form->handleRequest($request);
        // dump($formTopic);

        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // New Criteria
            $criteria = new Criteria();
            $criteria->setName($data->getName());

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($criteria);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');

            // Redirect to add descriptor
            return $this->redirectToRoute('', ['criteriaId' => $criteria->getId()]);
        }

        return $this->render('criterion/new.html.twig', [
            'form' => $form->createView(),
            'error' => false,
        ]);
    }
}
