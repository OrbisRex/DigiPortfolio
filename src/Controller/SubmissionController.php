<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Feedback;
use App\Entity\Log;
use App\Entity\ResourceFile;
// Entities
use App\Entity\Submission;
use App\Form\CommentFormType;
use App\Form\FeedbackFormType;
use App\Form\SubmissionFormType;
use App\Repository\AssignmentPersonRepository;
// Forms
use App\Repository\AssignmentRepository;
use App\Repository\CommentRepository;
use App\Repository\CriterionRepository;
// Repositories
use App\Repository\DescriptorRepository;
use App\Repository\FeedbackRepository;
use App\Repository\PersonRepository;
use App\Repository\ResourceFileRepository;
use App\Repository\SetRepository;
use App\Repository\SubjectRepository;
use App\Repository\SubmissionRepository;
use App\Repository\TopicRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of SubmissionController.
 *
 * @author David Ehrlich
 */
class SubmissionController extends AbstractController
{
    public function __construct(private readonly SubmissionRepository $submissionRepository, private readonly SubjectRepository $subjectRepository, private readonly TopicRepository $topicRepository, private readonly CriterionRepository $criterionRepository, private readonly DescriptorRepository $descriptorRepository, private readonly AssignmentRepository $assignmentRepository, private readonly AssignmentPersonRepository $assignmentPersonRepository, private readonly CommentRepository $commentRepository, private readonly SetRepository $setRepository, private readonly PersonRepository $personRepository, private readonly FeedbackRepository $feedbackRepository, private readonly ResourceFileRepository $resourceFileRepository, private readonly FileUploader $fileUploader)
    {
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/submission', name: 'submission')]
    public function index(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Fetch last assignments
        $submissions = $this->submissionRepository->findLastSubmissions($this->getUser(), 8);
        if (!$submissions) {
            $submissions = false;
        }

        // Generate set buttons
        $sets = $this->setRepository->findAll();

        // Fetch submissions by a Set
        $setId = $request->query->get('setId');
        if (!$setId) {
            $set = null;
            if ($this->isGranted('ROLE_TEACHER')) {
                $submissionsBySet[] = $this->submissionRepository->findByPeople([$this->getUser()->getUserIdentifier()]);
            }
        } else {
            $set = $this->setRepository->find($setId);
            if (!$set) {
                $submissionsBySet = null;
            } else {
                if ($this->isGranted('ROLE_TEACHER')) {
                    $people = $set->getPeople();
                    foreach ($people as $person) {
                        $submissionsBySet[] = $this->submissionRepository->findByPeople([$person]);
                    }
                } else {
                    $submissions = $this->submissionRepository->findBySet($set, $this->getUser()->getUserIdentifier());
                    if ($submissions) {
                        $submissionsBySet[] = $submissions;
                    } else {
                        $submissionsBySet = null;
                    }
                }
            }
        }

        return $this->render('submission/index.html.twig', [
            'submissions' => $submissions,
            'currentSet' => $set,
            'sets' => $sets,
            'submissionsBySet' => $submissionsBySet,
        ]);
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/submission/detail/{id}', name: 'submission-detail')]
    public function detail($id, Request $request)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        $criteriaDescriptors = [];

        // Fetch submission
        $submission = $this->submissionRepository->find($id);
        if (!$submission) {
            $submission = false;
        }

        // Submission Files
        $files = $this->resourceFileRepository->findBy(['submission' => $id], ['updatetime' => 'DESC']);
        if (!$files) {
            $files = false;
        }

        // Submission Comments
        $comments = $this->commentRepository->findBy(['submission' => $id], ['createtime' => 'DESC']);
        if (!$comments) {
            $comments = false;
        }

        // Fetch Criteria
        $criteria = $this->criterionRepository->findAll();
        foreach ($criteria as $criterion) {
            foreach ($criterion->getDescriptors() as $descriptor) {
                $criteriaDescriptors[$criterion->getName()][$descriptor->getId()] = $descriptor;
            }
        }

        // Feedback
        $feedback = $this->feedbackRepository->findOneBy(['submission' => $id]);
        if (!$feedback) {
            $feedback = new Feedback();
        }
        $form = $this->createForm(FeedbackFormType::class, $feedback, [
            'criteria' => $criteriaDescriptors,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form->getData();

            // Current timestemp
            $now = new \DateTime('now');

            // $comment->setText($data->getComment());
            // $comment->setType('submission');
            $feedback->setSubmission($submission);
            $feedback->setVersion(1);
            $feedback->setCreatetime($now);
            $feedback->setOwner($this->getUser());

            // Set Log Data
            $log = new Log();
            $log->setOperation('New Feedback for the submission id '.$id);
            $log->setPerson($this->getUser());
            $log->setResult('Success');
            $log->setTimestamp($now);

            // Add log record to Feedback
            $feedback->setLog($log);

            $entityManager->persist($feedback);
            $entityManager->persist($log);

            // Save data to the DB.
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to add descriptor
            return $this->redirectToRoute('submission-detail', ['id' => $id]);
        }

        // Comment
        $formComment = $this->createForm(CommentFormType::class);
        $formComment->handleRequest($request);

        // Process Comment
        if ($formComment->isSubmitted() && $formComment->isValid()) {
            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();

            $data = $formComment->getData();

            // Current timestemp
            $now = new \DateTime('now');

            // Create new Comment
            $comment = new Comment();
            $comment->setText($data->getText());
            $comment->setType('submission');
            $comment->setSubmission($submission);
            $comment->setCreatetime($now);
            $comment->setOwner($this->getUser());

            $entityManager->persist($comment);

            // Save data to the DB.
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to add descriptor
            return $this->redirectToRoute('submission-detail', ['id' => $id]);
        }

        return $this->render('submission/detail.html.twig', [
            'form' => $form->createView(),
            'formComment' => $formComment->createView(),
            'submission' => $submission,
            'files' => $files,
            'comments' => $comments,
        ]);
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/submission/new', name: 'new-submission')]
    public function new(Request $request)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');
        $assignments = [];

        // Check access for owner
        if ($this->isGranted('ROLE_TEACHER')) {
            $disabled = false;
            $assignments = $this->assignmentRepository->findAll();
        } else {
            $disabled = true;
            $personAssignments = $this->assignmentPersonRepository->findAssignmentsByStudent($this->getUser()->getId());
            foreach ($personAssignments as $personAssignment) {
                $assignments[] = $personAssignment->getAssignment();
            }
        }

        $students = $this->personRepository->findAllStudents();

        // New Submission Form
        $form = $this->createForm(SubmissionFormType::class, null, [
            'assignments' => $assignments,
            'students' => $students,
            'disabled_owner' => $disabled,
            'selected_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form->getData();

            // Current time
            $now = new \DateTime('now');

            // Create new Submission
            $submission = new Submission();
            $submission->setName($data->getName());
            $submission->setNote($data->getNote());
            $submission->setLink($data->getLink());
            $submission->setText($data->getText());
            $submission->setAssignment($data->getAssignment());
            $submission->setVersion(1);
            $submission->setCreatetime($now);
            if ($data->getOwner()) {
                $submission->setOwner($data->getOwner());
            } else {
                $submission->setOwner($this->getUser());
            }

            // Save enclosed files
            $files = $request->files->get('submission_form')['files'];
            foreach ($files as $index => $fileData) {
                $this->saveFile($fileData, $submission, $entityManager, $index);
            }

            // Set Log Data
            $log = new Log();
            $log->setOperation('New Submission '.$data->getName());
            $log->setPerson($this->getUser());
            $log->setResult('Success');
            $log->setTimestamp($now);

            // Add log record to submission
            $submission->setLog($log);

            $entityManager->persist($submission);
            $entityManager->persist($log);

            // Save data to the DB.
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to add descriptor
            return $this->redirectToRoute('submission');
        }

        return $this->render('submission/new.html.twig', [
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    #[\Symfony\Component\Routing\Attribute\Route(path: '/submission/edit/{id}', name: 'edit-submission')]
    public function edit(int $id, Request $request)
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$id) {
            throw $this->createNotFoundException('No Submission ID found.');
        }

        // Check access for owner
        if ($this->isGranted('ROLE_TEACHER')) {
            $disabled = false;
        } else {
            $disabled = true;
        }

        $submission = $this->submissionRepository->find($id);

        // Submission Files
        $files = $this->resourceFileRepository->findBy(['submission' => $id], ['updatetime' => 'DESC']);
        if (!$files) {
            $files = false;
        }

        // Submission Form
        $form = $this->createForm(SubmissionFormType::class, null, [
            'disabled_owner' => $disabled,
            'selected_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        // Process the form
        if ($form->isSubmitted() && $form->isValid()) {
            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();

            $data = $form->getData();

            // Current time and new version
            $now = new \DateTime('now');
            $version = $data->getVersion() + 1;

            $data->setName($data->getName());
            $data->setNote($data->getNote());
            $data->setLink($data->getLink());
            $data->setText($data->getText());
            $data->setAssignment($data->getAssignment());
            $data->setVersion($version);
            $data->setUpdatetime($now);
            if ($data->getOwner()) {
                $data->setOwner($data->getOwner());
            } else {
                $data->setOwner($this->getUser());
            }

            // Save enclosed files
            $files = $request->files->get('submission_form')['files'];
            foreach ($files as $index => $fileData) {
                $this->saveFile($fileData, $data, $entityManager, $index);
            }

            // Set Log Data
            $log = new Log();
            $log->setOperation('Edit Submission '.$data->getName());
            $log->setPerson($this->getUser());
            $log->setResult('Success');
            $log->setTimestamp($now);

            // Add log record to submission
            $data->setLog($log);

            $entityManager->persist($data);
            $entityManager->persist($log);

            // Save data to the DB.
            $entityManager->flush();

            $this->addFlash('success', 'Item has been saved.');

            // Redirect to assignment detail
            return $this->redirectToRoute('submission-detail', ['id' => $id]);
        }

        return $this->render('submission/edit.html.twig', [
            'submission' => $submission,
            'files' => $files,
            'form' => $form->createView(),
            'error' => false,
        ]);
    }

    private function saveFile($fileData, $submission, $entityManager, $index = false)
    {
        if ($fileData) {
            // Index number for friendly filename
            $index = +1;
            // Read file data for DB
            $fileSize = $fileData->getSize();
            $fileMimeType = $fileData->getMimeType();
            // Save file into file system
            $fileName = $this->fileUploader->upload($fileData);
            $filePath = $this->getParameter('app.targetDirectory').'/'.$fileName;
            // Save data into DB
            $file = new ResourceFile();
            $file->setName($submission->getName().' '.$index);
            $file->setPath($filePath);
            $file->setSize($fileSize);
            $file->setType($fileMimeType);
            $file->setOwner($this->getUser());
            $file->setUpdatetime(new \DateTimeImmutable());

            $file->setSubmission($submission);

            $entityManager->persist($file);
        }
    }
}
