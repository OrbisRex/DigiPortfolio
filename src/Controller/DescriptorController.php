<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Descriptor;

use App\Form\DescriptorFormType;

use App\Repository\CriterionRepository;
use App\Repository\DescriptorRepository;
use Doctrine\ORM\EntityManager;

/**
 * Description of DescriptorController.
 *
 * @author David Ehrlich
 */
class DescriptorController extends BasicController
{
    public function __construct(
        private readonly DescriptorRepository $descriptorRepository, 
        private readonly CriterionRepository $criterionRepository
    )
    {
    }

    #[Route(path: '/descriptor', name: 'descriptor')]
    public function index(): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // List descriptor
        $descriptors = $this->descriptorRepository->findByPerson($this->getUser());

        if (!$descriptors) {
            throw $this->createNotFoundException('No Descriptor found.');
        }

        return $this->render('descriptor/index.html.twig', [
            'descriptors' => $descriptors,
        ]);
    }

    #[Route(path: '/descriptor/new', name: 'new-descriptor')]
    public function new(Request $request, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // Keep a track with the criterion
        $criterionId = $request->query->get('criterionId');

        if (!$criterionId) {
            throw $this->createNotFoundException('No Criterion ID found.');
        }
        dump($criterionId);
        // Fetch criterion data
        $criterion = $this->criterionRepository->find($criterionId);
        if (!$criterion) {
            throw $this->createNotFoundException('No Criteria found.');
        }
        dump($criterion);

        // Read level for descriptor
        $level = $request->query->get('level');
        dump($level);

        $descriptorId = $request->query->get('descriptorId');
        dump($descriptorId);

        if ($descriptorId) {
            $description = $this->descriptorRepository->find($descriptorId)->getDescription();
            $name = $this->descriptorRepository->find($descriptorId)->getName();
        } else {
            $description = null;
            $name = null;
        }

        // New Descriptor Form
        $form = $this->createForm(DescriptorFormType::class, null, [
            'level_choice' => $level,
            'descriptor' => $description,
            'name' => $name,
        ]);

        $form->handleRequest($request);

        // Process Sing In form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Create new Criteria
            $descriptor = new Descriptor();
            $descriptor->setName($data->getName());
            $descriptor->setDescription($data->getDescription());
            $descriptor->setType($data->getType());
            $descriptor->setWeight($data->getWeight());
            $descriptor->setAuthor($this->getUser());
            //dump($criterion);
            //$descriptor->addCriterion($criterion);

            // Save data to the DB.
            $entityManager->persist($descriptor);
            $entityManager->flush();

            $this->addFlash('success', 'Descriptor has been saved.');

            //return $this->redirectToRoute('new-descriptor', ['criterionId' => $criterion->getId(), 'descriptorId' => $descriptor->getId(), 'level' => $data->getType()]);
        }

        return $this->render('descriptor/new.html.twig', [
            'criterion' => $criterion,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/descriptor/edit/{criterionId}/{id}', name: 'edit-descriptor')]
    public function edit(int $criterionId, Descriptor $descriptor, Request $request, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        if (!$criterionId) {
            throw $this->createNotFoundException('No Criterion or Descriptor ID found.');
        }

        // Fetch related criterion
        $criterion = $this->criterionRepository->find($criterionId);

        // Descriptor Form
        $form = $this->createForm(DescriptorFormType::class, $descriptor, [
            'name' => $descriptor->getName(),
            'descriptor' => $descriptor->getDescription(),
            'level_choice' => $descriptor->getType(),
        ]);
        $form->handleRequest($request);

        // Process Sing In form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $descriptor->setName($data->getName());
            $descriptor->setDescription($data->getDescription());
            $descriptor->setType($data->getType());
            $descriptor->setWeight($data->getWeight());
            $descriptor->setAuthor($this->getUser());
            // $descriptor->addCriteria($criteria);

            // Save data to the DB.
            $entityManager->persist($descriptor);
            $entityManager->flush();

            return $this->redirectToRoute('edit-descriptor', ['criterionId' => $criterionId]);
        }

        return $this->render('descriptor/edit.html.twig', [
            'criterion' => $criterion,
            'descriptor' => $descriptor,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }
}
