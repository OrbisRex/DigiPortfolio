<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

//Forms
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

//Entities
use App\Entity\Assignment;
use App\Entity\AssignmentPerson;

//Forms
use App\Form\AssignmentFormType;

//Repositories
use App\Repository\AssignmentRepository;
use App\Repository\SubjectRepository;
use App\Repository\TopicRepository;
use App\Repository\CriterionRepository;
use App\Repository\DescriptorRepository;
use App\Repository\AssignmentPersonRepository;
use App\Repository\CommentRepository;
use App\Repository\SetRepository;
use App\Repository\PersonRepository;

/**
 * Description of AssignmentController
 *
 * @author David Ehrlich
 */
class AssignmentController extends BasicController
{
    /**
     * @var AssignmentRepository
     */
    private $assignmentRepository;
    /**
     * @var SubjectRepository
     */
    private $subjectRepository;
    /**
     * @var CriterionRepository
     */
    private $criterionRepository;
    /**
     * @var DescriptorRepository
     */
    private $descriptorRepository;
    /**
     * @var TopicRepository
     */
    private $topicRepository;
    /**
     * @var AssignmentPersonRepository
     */
    private $assignmentPersonRepository;
    /**
     * @var CommentRepository
     */    
    private $commentRepository;
    /**
     * @var SetRepository
     */    
    private $setRepository;
    /**
     * @var PersonRepository
     */    
    private $personRepository;
    
    public function __construct(
            AssignmentRepository $assignmentRepository,
            SubjectRepository $subjectRepository,
            TopicRepository $topicRepository,
            CriterionRepository $criterionRepository,
            DescriptorRepository $descriptorRepository,
            AssignmentPersonRepository $assignmentPersonRepository,
            CommentRepository $commentRepository,
            SetRepository $setRepository,
            PersonRepository $personRepository
    )
    {
        $this->assignmentRepository = $assignmentRepository;
        $this->subjectRepository = $subjectRepository;
        $this->topicRepository = $topicRepository;
        $this->criterionRepository = $criterionRepository;
        $this->descriptorRepository = $descriptorRepository;
        $this->assignmentPersonRepository = $assignmentPersonRepository;
        $this->commentRepository = $commentRepository;
        $this->setRepository = $setRepository;
        $this->personRepository = $personRepository;
    }

    /**
    * @Route("/assignment", name="assignment")
    */
    public function index(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        //Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        //Fetch last assignments
        if($this->isGranted('ROLE_TEACHER')){
            $assignments = $this->assignmentPersonRepository->findLastAssignmentsByTeacher($this->getUser()->getId(), 8);            
        } else {
            $assignments = $this->assignmentPersonRepository->findLastAssignments($this->getUser()->getId(), 8);
        }
        if(!$assignments) {
            $assignments = FALSE;
        }

        //Generate subject buttons
        $subjects = $this->subjectRepository->findAll();
        
        //Fetch assignments by Subject
        $subjectId = $request->query->get('subjectId');

        if($subjectId){
            $assignmentsBySubject = $this->groupAssignmentsBySubject($subjectId);
            $currentSubject = $this->subjectRepository->findOneById($subjectId);
        } else {
            $assignmentsBySubject = $this->groupAssignmentsBySubject();
            $currentSubject = null;
        }

        //Generate topic buttons
        $topics = $this->topicRepository->findAll();
        
        //Fetch assignments by Topic
        $topicId = $request->query->get('topicId');
        if($topicId){
            $assignmentsByTopic = $this->groupAssignmentsByTopic($topicId);
            $currentTopic = $this->topicRepository->findOneById($topicId);
        } else {
            $assignmentsByTopic = $this->groupAssignmentsByTopic();
            $currentTopic = null;
        }

        return $this->render('assignment/index.html.twig', array(
            'assignments' => $assignments,
            'subjects' => $subjects,
            'topics' => $topics,
            'assignmentsBySubject' => $assignmentsBySubject,
            'currentSubject' => $currentSubject,
            'assignmentsByTopic' => $assignmentsByTopic,
            'currentTopic' => $currentTopic,
        ));
    }

    
    /**
     * @Route("/assignment/detail/{id}", name="assignment-detail")
     */
    public function detail(int $id): \Symfony\Component\HttpFoundation\Response 
    {
        //Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        $studentList = FALSE;        
        if(!$id) {
            throw $this->createNotFoundException(
                'No Assignment ID found.'
            );
        }
        
        //Fetch descriptor for assignment
        $assignment = $this->assignmentRepository->find($id);
        if(!$assignment) {
            throw $this->createNotFoundException(
                'No Assignment found.'
            );
        }
        
        //Fetch criteria for assignment and group them by name
        $criteria = $assignment->getCriteria();

        //Fetch student's list
        $students = $this->assignmentPersonRepository->findByAssignment($assignment->getId());

        return $this->render('assignment/detail.html.twig', array(
            'assignment' => $assignment,
            'students' => $students,
            'criteria' => $criteria,
        ));
    }

    
    /**
     * @Route("/assignment/new", name="new-assignment")
     * 
     */
    public function new(Request $request)
    {     
        //Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        //New Assignment Form
        $form = $this->createForm(AssignmentFormType::class);
        $form->handleRequest($request);
        
        //Process form
        if($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();
            //$subject = $this->findById($entityManager, 'App:Subject', $data['subject']);
            //$topic = $this->findById($entityManager, 'App:Topic', $data['topic']);
            
            //Current time
            $now = new \DateTime('now');
            
            //Create new Assignment
            $assignment = new Assignment();
            $assignment->setName($data->getName());
            $assignment->setState('public');
            $assignment->setTeacher($this->getUser());
            $assignment->setSubject($data->getSubject());
            $assignment->setTopic($data->getTopic());
            $assignment->setNote($data->getNote());
            $assignment->setUpdatetime($now);
            $assignment->setPerson($this->getUser());
            foreach($data->getCriteria() as $criterion)
            {
                $assignment->addCriterion($criterion);
            }
            
            //Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assignment);

            //Copy assignment for each student in group.
            $students = $data->getSet()->getPeople();
            foreach($students as $student)
            {
                $assign = new AssignmentPerson();
                
                $assign->setPerson($student);
                $assign->setAssignment($assignment);
                
                $entityManager->persist($assign);
            }

            //Save data to the DB.
            $entityManager->flush();
            
            //Redirect to add descriptor 
            return $this->redirectToRoute('assignment');            
        }        
        
        return $this->render('assignment/new.html.twig', array(
            'form' => $form->createView(),
            'error' => FALSE,
        ));
    }
    
    
    /**
     * @Route("/assignment/edit/{id}", name="edit-assignment")
     * 
     */
    public function edit(int $id, Request $request)
    {     
        //Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        if(!$id) {
            throw $this->createNotFoundException(
                'No Assignment ID found.'
            );
        }
        
        $assignment = $this->assignmentRepository->find($id);
        
        //Assignment Form
        $form = $this->createForm(AssignmentFormType::class, $assignment);
        $form->handleRequest($request);
        
        //Process Sing In form
        if($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();
            
            //$subject = $this->subjectRepository->find($data->getSubject());
            //$topic = $this->topicRepository->find($data->getTopic());
            
            //Current time
            $now = new \DateTime('now');
            
            //Update Assignment
            $data->setName($data->getName());
            $data->setSubject($data->getSubject());
            $data->setTopic($data->getTopic());
            $data->setNote($data->getNote());
            $data->setUpdatetime($now);
            
            //Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);

            //Copy assignment for each student in set.
            //TODO: Assignment has only one set but students are added in every update in edit form.
            $students = $data->getSet()->getPeople();
            foreach($students as $student)
            {
                $assign = new AssignmentPerson();
                
                $assign->setPerson($student);
                $assign->setAssignment($data);
                
                $entityManager->persist($assign);
            }

            //Save data to the DB.
            $entityManager->flush();
            
            //Redirect to assignment detail 
            return $this->redirectToRoute('assignment-detail', array('id' => $data->getId()));            
        }        
        
        return $this->render('assignment/edit.html.twig', array(
            'assignment' => $assignment,
            'form' => $form->createView(),
            'error' => FALSE,
        ));
    }
    
    private function findAllStudents()
    {
        /**
         * @var array Array of students.
         */        
        $students = array();
        
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
        if(!$criterionNames) {
            $criteria = FALSE;
        } else {
            foreach($criterionNames as $name) {
                $criteriaData = $this->getDoctrine()->getRepository('App:Criterion')->findBy(
                        array('assignment' => $assignmentId, 'name' => $name['name']));
                
                $criteria[$name['name']] = $criteriaData;
            }
        } 
        
        return $criteria;
    }
    
    private function groupAssignmentsBySubject($subjectId = false)
    {
        if($subjectId != false) {
            $subjects = $this->subjectRepository->findById($subjectId);
        }

        if(empty($subjects)) {
            if($this->isGranted('ROLE_TEACHER')) {
                $assignments = $this->assignmentPersonRepository
                        ->findAssignmentsByTeacher($this->getUser()->getId());
            } else {
                $assignments = $this->assignmentPersonRepository
                        ->findAssignmentsByStudent($this->getUser()->getId());
            }
        } else {
            foreach ($subjects as $subject) {
                if($this->isGranted('ROLE_TEACHER')) {
                    $assignments = $this->assignmentPersonRepository
                            ->findAssignmentsByTeacherForSubject($this->getUser()->getId(), $subject->getId());
                } else {
                    $assignments = $this->assignmentPersonRepository
                            ->findAssignmentsByStudentBySubject($this->getUser()->getId(), $subject->getId());
                }
            }
        }
   
        return $assignments;
    }
    
    private function groupAssignmentsByTopic($topicId = false)
    {
        if($topicId != false) {
            $topics = $this->topicRepository->findById($topicId);
        }
        
        if(empty($topics)) {
            if($this->isGranted('ROLE_TEACHER')) {
                $assignments = $this->assignmentPersonRepository
                        ->findAssignmentsByTeacher($this->getUser()->getId());
            } else {
                $assignments = $this->assignmentPersonRepository
                        ->findAssignmentsByStudent($this->getUser()->getId());
            }
        } else {
            foreach($topics as $topic) {
                if($this->isGranted('ROLE_TEACHER')) {
                    $assignments = $this->assignmentPersonRepository
                            ->findAssignmentsByTeacherForTopic($this->getUser()->getId(), $topic->getId());
                } else {
                    $assignments = $this->assignmentPersonRepository
                            ->findAssignmentsByStudentForTopic($this->getUser()->getId(), $topic->getId());
                }
            }            
        }
                
        return $assignments;
    }
}
