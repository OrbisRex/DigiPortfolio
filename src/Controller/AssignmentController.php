<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

use App\Entity\Assignment;
use App\Entity\AssignmentPerson;
use App\Form\AssignmentFormType;
use App\Repository\AssignmentPersonRepository;
use App\Repository\AssignmentRepository;
use App\Repository\CommentRepository;
use App\Repository\CriterionRepository;
use App\Repository\DescriptorRepository;
use App\Repository\PersonRepository;
use App\Repository\SetRepository;
use App\Repository\SubjectRepository;
use App\Repository\TopicRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of AssignmentController.
 *
 * @author David Ehrlich
 */
class AssignmentController extends BasicController
{
    public function __construct(
        private readonly AssignmentRepository $assignmentRepository,
        private readonly SubjectRepository $subjectRepository,
        private readonly TopicRepository $topicRepository,
        private readonly CriterionRepository $criterionRepository,
        private readonly DescriptorRepository $descriptorRepository,
        private readonly AssignmentPersonRepository $assignmentPersonRepository,
        private readonly CommentRepository $commentRepository,
        private readonly SetRepository $setRepository,
        private readonly PersonRepository $personRepository,
        EntityManagerInterface $entityManager
    )
    {
    }

    #[Route(path: '/assignment', name: 'assignment')]
    public function index(Request $request): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Fetch last assignments
        $assignments = $this->assignmentPersonRepository->findLastAssignments($this->getUser(), 8);
        dump($assignments);
        if (!$assignments) {
            $assignments = false;
        }

        // Generate subject buttons
        $subjects = $this->subjectRepository->findAll();

        // Fetch assignments by Subject
        $subjectId = $request->query->get('subjectId');

        if ($subjectId) {
            $assignmentsBySubject = $this->groupAssignmentsBySubject($subjectId);
            $currentSubject = $this->subjectRepository->findOneById($subjectId);
        } else {
            $assignmentsBySubject = $this->groupAssignmentsBySubject();
            $currentSubject = null;
        }

        // Generate topic buttons
        $topics = $this->topicRepository->findAll();

        // Fetch assignments by Topic
        $topicId = $request->query->get('topicId');

        if ($topicId) {
            $assignmentsByTopic = $this->groupAssignmentsByTopic($topicId);
            $currentTopic = $this->topicRepository->findOneById($topicId);
        } else {
            $assignmentsByTopic = $this->groupAssignmentsByTopic();
            $currentTopic = null;
        }

        return $this->render('assignment/index.html.twig', [
            'assignments' => $assignments,
            'subjects' => $subjects,
            'topics' => $topics,
            'assignmentsBySubject' => $assignmentsBySubject,
            'currentSubject' => $currentSubject,
            'assignmentsByTopic' => $assignmentsByTopic,
            'currentTopic' => $currentTopic,
        ]);
    }

    #[Route(path: '/assignment/detail/{id}', name: 'assignment-detail')]
    public function detail(Assignment $assignment): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Fetch criteria for assignment and group them by name
        $criteria = $assignment->getCriteria();

        // Fetch student's list
        $students = $this->assignmentPersonRepository->findStudentsByAssignment($assignment);

        return $this->render('assignment/detail.html.twig', [
            'assignment' => $assignment,
            'students' => $students,
            'criteria' => $criteria,
        ]);
    }

    #[Route(path: '/assignment/new', name: 'new-assignment')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // New Assignment Form
        $form = $this->createForm(AssignmentFormType::class);
        $form->handleRequest($request);

        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // $subject = $this->findById($entityManager, 'App:Subject', $data['subject']);
            // $topic = $this->findById($entityManager, 'App:Topic', $data['topic']);

            // Current time
            $now = new DateTimeImmutable('now');

            // Create new Assignment
            $assignment = new Assignment();
            $assignment->setName($data->getName());
            $assignment->setState('public');
            $assignment->setSubject($data->getSubject());
            $assignment->setTopic($data->getTopic());
            $assignment->setNote($data->getNote());
            $assignment->setUpdatetime($now);
            //$assignment->addPerson($this->getUser());
            foreach ($data->getCriteria() as $criterion) {
                $assignment->addCriterion($criterion);
            }

            $entityManager->persist($assignment);

            // Copy assignment for each student in group.
            $students = $data->getSet()->getPeople();
            foreach ($students as $student) {
                $assign = new AssignmentPerson();

                $assign->setPerson($student);
                $assign->setAssignment($assignment);

                $entityManager->persist($assign);
            }

            // Save data to the DB.
            $entityManager->flush();

            // Redirect to add descriptor
            return $this->redirectToRoute('assignment');
        }

        return $this->render('assignment/new.html.twig', [
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[Route(path: '/assignment/edit/{id}', name: 'edit-assignment')]
    public function edit(int $id, Request $request)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        if (!$id) {
            throw $this->createNotFoundException('No Assignment ID found.');
        }

        $assignment = $this->assignmentRepository->find($id);

        // Assignment Form
        $form = $this->createForm(AssignmentFormType::class, $assignment);
        $form->handleRequest($request);

        // Process Sing In form
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // $subject = $this->subjectRepository->find($data->getSubject());
            // $topic = $this->topicRepository->find($data->getTopic());

            // Current time
            $now = new DateTime('now');

            // Update Assignment
            $data->setName($data->getName());
            $data->setSubject($data->getSubject());
            $data->setTopic($data->getTopic());
            $data->setNote($data->getNote());
            $data->setUpdatetime($now);

            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);

            // Copy assignment for each student in set.
            // TODO: Assignment has only one set but students are added in every update in edit form.
            $students = $data->getSet()->getPeople();
            foreach ($students as $student) {
                $assign = new AssignmentPerson();

                $assign->setPerson($student);
                $assign->setAssignment($data);

                $entityManager->persist($assign);
            }

            // Save data to the DB.
            $entityManager->flush();

            // Redirect to assignment detail
            return $this->redirectToRoute('assignment-detail', ['id' => $data->getId()]);
        }

        return $this->render('assignment/edit.html.twig', [
            'assignment' => $assignment,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    private function findAllStudents()
    {
        /**
         * @var array array of students
         */
        $students = [];

        $studentData = $this->personRepository->findAllStudents();
        foreach ($studentData as $studentEntity) {
            $students[$studentEntity->getName()] = $studentEntity->getId();
        }

        return $students;
    }

    private function findStudentsInGroup($group)
    {
        $students = $this->personRepository->findStudentsFromGroup($group);

        return $students;
    }

    private function groupCriteriaByName($assignmentId)
    {
        $criterionNames = $this->getDoctrine()->getRepository('App:Criterion')->findNames($assignmentId);
        if (!$criterionNames) {
            $criteria = false;
        } else {
            foreach ($criterionNames as $name) {
                $criteriaData = $this->getDoctrine()->getRepository('App:Criterion')->findBy(
                    ['assignment' => $assignmentId, 'name' => $name['name']]
                );

                $criteria[$name['name']] = $criteriaData;
            }
        }

        return $criteria;
    }

    private function groupAssignmentsBySubject($subjectId = false)
    {
        if ($subjectId != false) {
            $subjects = $this->subjectRepository->findById($subjectId);
        }

        if (empty($subjects)) {
            $assignments = $this->assignmentPersonRepository
                ->findAssignmentsByPerson($this->getUser());
        } else {
            foreach ($subjects as $subject) {
                $assignments = $this->assignmentPersonRepository
                    ->findAssignmentsByPersonForSubject($this->getUser(), $subject);
            }
        }

        return $assignments;
    }

    private function groupAssignmentsByTopic($topicId = false)
    {
        if ($topicId != false) {
            $topics = $this->topicRepository->findById($topicId);
        }

        if (empty($topics)) {
            $assignments = $this->assignmentPersonRepository
                ->findAssignmentsByPerson($this->getUser());
        } else {
            foreach ($topics as $topic) {
                $assignments = $this->assignmentPersonRepository
                    ->findAssignmentsByPersonForTopic($this->getUser(), $topic);
            }
        }

        return $assignments;
    }
}
