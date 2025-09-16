<?php

namespace App\Controller;

use App\Entity\Set;
use App\Entity\Subject;
use App\Entity\Topic;
// Entities
use App\Form\SetEmbededFormType;
use App\Form\SetFormType;
use App\Form\SubjectFormType;
// Forms
use App\Form\TopicFormType;
use App\Repository\CriterionRepository;
use App\Repository\SetRepository;
use App\Repository\SubjectRepository;
// Repositories
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SettingsController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route(path: '/settings', name: 'settings')]
    public function index(
        Request $request,
        SubjectRepository $subjectRepository,
        TopicRepository $topicRepository,
        SetRepository $setRepository,
        CriterionRepository $criterionRepository,
    ) {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // SUBJECTS///////////////////
        // Generate subject buttons
        $subjects = $subjectRepository->findAll();

        // New Subject Form
        $formSubject = $this->createForm(SubjectFormType::class);
        $formSubject->handleRequest($request);
        // dump($formSubject);

        // Process Subject form
        if ($formSubject->isSubmitted() && $formSubject->isValid()) {
            $newSubject = $formSubject->getData();
            // dump($newSubject);

            // New subject
            $subject = new Subject();
            $subject->setName($newSubject->getName());
            $subject->addPerson($this->getUser());

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($subject);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');

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

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');

            // Redirect to Settings Page
            return $this->redirectToRoute('settings', ['_fragment' => 'topic']);
        }

        // SETS///////////////////
        // Generate set buttons
        $sets = $setRepository->findAll();

        // New Set
        $formSet = $this->createForm(SetEmbededFormType::class);
        $formSet->handleRequest($request);
        // dump($formTopic);

        // Process form
        if ($formSet->isSubmitted() && $formSet->isValid()) {
            $data = $formSet->getData();

            // New Set
            $set = new Set();
            $set->setName($data->getName());
            $set->setType('Organisation');
            $set->addPerson($this->getUser());
            // TODO: Improvement ^.

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($set);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');

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

    #[\Symfony\Component\Routing\Attribute\Route(path: '/settings/subject/{id}', name: 'settings-subject')]
    public function subject($id, Request $request, SubjectRepository $subjectRepository): \Symfony\Component\HttpFoundation\Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $subject = $subjectRepository->find($id);

        if (!$subject) {
            throw $this->createNotFoundException('No subject found for id '.$id);
        }

        // New Subject Form
        $formSubject = $this->createForm(SubjectFormType::class, $subject);
        $formSubject->handleRequest($request);
        // dump($formSubject);

        // Process Subject form
        if ($formSubject->isSubmitted() && $formSubject->isValid()) {
            $newSubject = $formSubject->getData();
            // dump($newSubject);

            $subject->setName($newSubject->getName());

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($subject);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');

            return $this->render('settings/subject.html.twig', [
                'subject' => $subject,
                'formSubject' => $formSubject->createView(),
                'error' => false,
            ]);
        }

        return $this->render('settings/subject.html.twig', [
            'subject' => $subject,
            'formSubject' => $formSubject->createView(),
            'error' => false,
        ]);
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/settings/topic/{id}', name: 'settings-topic')]
    public function topic($id, Request $request, TopicRepository $topicRepository): \Symfony\Component\HttpFoundation\Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $topic = $topicRepository->find($id);

        if (!$topic) {
            throw $this->createNotFoundException('No topic found for id '.$id);
        }

        // New Subject Form
        $formTopic = $this->createForm(TopicFormType::class, $topic);
        $formTopic->handleRequest($request);
        // dump($formSubject);

        // Process Subject form
        if ($formTopic->isSubmitted() && $formTopic->isValid()) {
            $newTopic = $formTopic->getData();
            // dump($newSubject);

            $topic->setName($newTopic->getName());

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($topic);
            $entityManager->flush();

            $this->addFlash('notice', 'Item has been saved.');
        }

        return $this->render('settings/topic.html.twig', [
            'topic' => $topic,
            'formTopic' => $formTopic->createView(),
            'error' => false,
        ]);
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/settings/set/{id}', name: 'settings-set')]
    public function set($id, Request $request, SetRepository $setRepository): \Symfony\Component\HttpFoundation\Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        $set = $setRepository->find($id);
        $members = $set->getPeople();

        if (!$set) {
            throw $this->createNotFoundException('No set found for id '.$id);
        }

        $form = $this->createForm(SetFormType::class, $set);
        $form->handleRequest($request);

        // Process Form form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dump($newSubject);

            $set->setName($data->getName());
            // People are automaticaly updated

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Save data to the DB.
            $entityManager->persist($set);
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');
        }

        return $this->render('settings/set.html.twig', [
            'set' => $set,
            'members' => $members,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }
}
