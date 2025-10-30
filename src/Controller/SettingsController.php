<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Set;
use App\Entity\Subject;
use App\Entity\Topic;
use App\Form\SetEmbededFormType;
use App\Form\SetFormType;
use App\Form\SubjectFormType;
use App\Form\TopicFormType;
use App\Repository\CriterionRepository;
use App\Repository\SetRepository;
use App\Repository\SubjectRepository;
use App\Repository\TopicRepository;

class SettingsController extends AbstractController
{
    #[Route(path: '/settings', name: 'settings')]
    public function index(
        Request $request,
        SubjectRepository $subjectRepository,
        TopicRepository $topicRepository,
        SetRepository $setRepository,
        CriterionRepository $criterionRepository,
        EntityManagerInterface $entityManager,
    ): RedirectResponse|Response {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // SUBJECTS///////////////////
        // Generate subject buttons
        $subjects = $subjectRepository->findAll();

        // New Subject Form
        $formSubject = $this->createForm(SubjectFormType::class);
        $formSubject->handleRequest($request);

        // Process Subject form
        if ($formSubject->isSubmitted() && $formSubject->isValid()) {
            $newSubject = $formSubject->getData();

            // New subject
            $subject = new Subject();
            $subject->setName($newSubject->getName());
            $subject->addPerson($this->getUser());

            // Save data to the DB.
            $entityManager->persist($subject);
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to Settings Page
            return $this->redirectToRoute('settings', ['_fragment' => 'subject']);
        }

        // TOPICS///////////////////
        // Generate topic buttons
        $topics = $topicRepository->findAll();

        // New Topic Form
        $formTopic = $this->createForm(TopicFormType::class);
        $formTopic->handleRequest($request);
        // dump($formTopic);

        // Process Topic form
        if ($formTopic->isSubmitted() && $formTopic->isValid()) {
            $newTopic = $formTopic->getData();

            // New Topic
            $topic = new Topic();
            $topic->setName($newTopic->getName());
            $topic->setPerson($this->getUser());

            // Save data to the DB.
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to Settings Page
            return $this->redirectToRoute('settings', ['_fragment' => 'topic']);
        }

        // SETS///////////////////
        // Generate set buttons
        $sets = $setRepository->findAll();

        // New Set
        $formSet = $this->createForm(SetEmbededFormType::class);
        $formSet->handleRequest($request);

        // Process form
        if ($formSet->isSubmitted() && $formSet->isValid()) {
            $data = $formSet->getData();

            // New Set
            $set = new Set();
            $set->setName($data->getName());
            $set->setType('Organisation');
            $set->addPerson($this->getUser());
            // TODO: Improvement ^.

            // Save data to the DB.
            $entityManager->persist($set);
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to Settings Page
            return $this->redirectToRoute('settings', ['_fragment' => 'set']);
        }

        return $this->render('settings/index.html.twig', [
            'subjects' => $subjects,
            'topics' => $topics,
            'sets' => $sets,
            'formSubject' => $formSubject->createView(),
            'formTopic' => $formTopic->createView(),
            'formSet' => $formSet->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/settings/subject/{id?}', name: 'settings-subject')]
    public function subject(Request $request, ?Subject $subject, EntityManagerInterface $entityManager): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // New Subject Form
        $formSubject = $this->createForm(SubjectFormType::class, $subject);
        $formSubject->handleRequest($request);

        // Process Subject form
        if ($formSubject->isSubmitted() && $formSubject->isValid()) {
            $newSubject = $formSubject->getData();

            // New item
            if ($subject === null) { $subject = new Subject(); }

            $subject->setName($newSubject->getName());
            $subject->addPerson($this->getUser());

            // Save data to the DB.
            $entityManager->persist($subject);
            $entityManager->flush();

            $this->addFlash('success', 'Subject has been saved.');
        }

        return $this->render('settings/subject.html.twig', [
            'subject' => $subject,
            'formSubject' => $formSubject->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/settings/topic/{id?}', name: 'settings-topic')]
    public function topic(Request $request, ?Topic $topic, EntityManagerInterface $entityManager): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // New form
        $formTopic = $this->createForm(TopicFormType::class, $topic);
        $formTopic->handleRequest($request);

        // Process form
        if ($formTopic->isSubmitted() && $formTopic->isValid()) {
            $newTopic = $formTopic->getData();

            // New item
            if ($topic === null) { $topic = new Topic(); }

            $topic->setName($newTopic->getName());
            $topic->setPerson($this->getUser());

            // Save data to the DB.
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('success', 'Topic has been saved.');
        }

        return $this->render('settings/topic.html.twig', [
            'topic' => $topic,
            'formTopic' => $formTopic->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/settings/set/{id?}', name: 'settings-set')]
    public function set(Request $request, ?Set $set, EntityManagerInterface $entityManager): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $formSet = $this->createForm(SetFormType::class, $set);
        $formSet->handleRequest($request);

        // Process Form form
        if ($formSet->isSubmitted() && $formSet->isValid()) {
            $data = $formSet->getData();

            // New item
            if ($set === null) { $set = new Set(); } 

            $set->setName($data->getName());
            $set->setType($data->getType());
            // People are automaticaly updated

            // Save data to the DB.
            $entityManager->persist($set);
            $entityManager->flush();

            $this->addFlash('success', 'Set has been saved.');
        }

        return $this->render('settings/set.html.twig', [
            'set' => $set,
            'form' => $formSet->createView(),
            'error' => false,
        ]);
    }
}
