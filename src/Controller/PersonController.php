<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Person;
use App\Entity\Set;
use App\Form\ProfileFormType;
use App\Repository\AssignmentPersonRepository;
use App\Repository\PersonRepository;
use App\Repository\ResourceFileRepository;
use App\Repository\SetRepository;
use App\Repository\SubmissionRepository;
use App\Service\CsvImporter;

class PersonController extends AbstractController
{
    public function __construct(private readonly Security $security)
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
        Request $request,
        Person $person,
        SetRepository $setRepository,
        AssignmentPersonRepository $assignmentPersonRepository,
        SubmissionRepository $submissionRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Find all assignments for user
        $assignments = $assignmentPersonRepository->findByPerson($person->getId());

        // Find all submissions for user
        $submissions = $submissionRepository->findByPeople([$person->getId()]);
        $currentSets = $person->getSets()->toArray();

        // Edit User Profile
        $form = $this->createForm(ProfileFormType::class, $person);
        $form->handleRequest($request);
        // dump($formSubject);

        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $person->setName($data->getName());
            $person->setEmail($data->getEmail());
            // encode the plain password
            $person->setPassword($data->getPassword());
            $person->setDisabled($data->getDisabled());
            $person->setRoles($data->getRoles());

            // Remove old sets
            foreach($currentSets as $set) {
                $set->removePerson($person);
            }

            // Add new sets
            foreach ($data->getSets() as $set) {
                $set->addPerson($person);
            }

            // Save data to the DB.
            $entityManager->persist($set);
            $entityManager->persist($person);
            $entityManager->flush();

            $this->addFlash('success', 'Profile has been saved.');
            return $this->redirectToRoute('person');
        }

        return $this->render('person/profile.html.twig', [
            'person' => $person,
            'assignments' => $assignments,
            'submissions' => $submissions,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/person/import/{id}', name: 'import-person')]
    public function import(
        int $id,
        CsvImporter $csvImporter,
        UserPasswordHasherInterface $passwordHasher,
        ResourceFileRepository $resourceFileRepository,
        PersonRepository $personRepository,
        SetRepository $setRepository,
    ): RedirectResponse {
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
