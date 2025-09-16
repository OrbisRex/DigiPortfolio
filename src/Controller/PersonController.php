<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\Set;
use App\Form\ProfileFormType;
use App\Repository\AssignmentPersonRepository;
use App\Repository\PersonRepository;
use App\Repository\ResourceFileRepository;
// Services
use App\Repository\SetRepository;
// Entities
use App\Repository\SubmissionRepository;
use App\Service\CsvImporter;
// Forms
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Repositories
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PersonController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }

    #[Route(path: '/person', name: 'person')]
    public function index(PersonRepository $personRepository, ResourceFileRepository $resourceFileRepository): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $people = $personRepository->findAll();

        // User Import Files
        $otherFiles = $resourceFileRepository->findOtherFilesThen('image%', $this->getUser());
        if (!$otherFiles) {
            $otherFiles = false;
        }

        return $this->render('person/index.html.twig', [
            'people' => $people,
            'otherFiles' => $otherFiles,
        ]);
    }

    #[Route(path: '/person/profile/{id}', name: 'person-profile')]
    public function profile(
        $id,
        Request $request,
        PersonRepository $personRepository,
        AssignmentPersonRepository $assignmentPersonRepository,
        SubmissionRepository $submissionRepository,
    ): Response {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $personRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }

        // Find all assignments for user
        $assignments = $assignmentPersonRepository->findByPerson($user->getId());

        // Find all submissions for user
        $submissions = $submissionRepository->findByPeople([$user->getId()]);

        // Edit User Profile
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        // dump($formSubject);

        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dump($data);

            $user->setName($data->getName());
            $user->setEmail($data->getEmail());
            // encode the plain password
            $user->setPassword($data->getPassword());
            $user->setDisabled($data->getDisabled());
            $user->setRoles($data->getRoles());
            // Set is automatically updated

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');
        }

        return $this->render('person/profile.html.twig', [
            'user' => $user,
            'assignments' => $assignments,
            'submissions' => $submissions,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/person/import/{id}', name: 'import-person')]
    public function import(
        $id,
        CsvImporter $csvImporter,
        UserPasswordHasherInterface $passwordHasher,
        ResourceFileRepository $resourceFileRepository,
        PersonRepository $personRepository,
        SetRepository $setRepository,
    ): \Symfony\Component\HttpFoundation\RedirectResponse {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
        $newUserCount = 0;

        $csvFile = $resourceFileRepository->find($id);
        if (!$csvFile) {
            throw $this->createNotFoundException('No user import file found for id '.$id);
        }

        $data = $csvImporter->userImport($csvFile->getName());
        foreach ($data as $person) {
            $existingUser = $personRepository->findOneBy(['email' => $person['email']]);
            if (!$existingUser) {
                $user = new Person();
                $user->setName($person['name']);
                $user->setEmail($person['email']);
                // encode the plain password
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $person['password'],
                    )
                );
                $user->setRoles(['ROLE_USER', 'ROLE_STUDENT']);

                $set = $setRepository->findOneBy(['name' => $person['set']]);
                if (!$set) {
                    $set = new Set();
                    $set->setName($person['set']);
                    $set->setType('Organisation');
                }
                $user->addSet($set);
                $user->setDisabled(0);

                // Doctrine Entity Manager
                $entityManager = $this->getDoctrine()->getManager();
                // Save data to the DB.
                $entityManager->persist($user);
                $entityManager->flush();

                ++$newUserCount;
            }
        }

        $this->addFlash('success', $newUserCount.' new users have been imported.');

        // Redirect to File Management
        return $this->redirectToRoute('person');
    }
}
