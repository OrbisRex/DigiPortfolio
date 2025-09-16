<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use DateTimeImmutable;
use App\Entity\ResourceFile;
use App\Form\FileFormType;
use App\Repository\ResourceFileRepository;
use App\Service\FileUploader;
// Entities
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Repositories
use Symfony\Component\HttpFoundation\Request;

// Forms

class ResourceFileController extends AbstractController
{
    #[Route(path: '/file', name: 'file')]
    public function index(Request $request, ResourceFileRepository $resourceFileRepository, FileUploader $fileUploader): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // NEW Files
        $files = $resourceFileRepository->findBy(['owner' => $this->getUser()], ['updatetime' => 'DESC'], 6);
        if (!$files) {
            $files = false;
        }

        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);
        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            // Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();

            $newFiles = $request->files->get('file_form')['files'];
            foreach ($newFiles as $fileData) {
                if ($fileData) {
                    // Read file data for DB
                    $fileSize = $fileData->getSize();
                    $fileMimeType = $fileData->getMimeType();
                    // Save file into file system
                    $fileName = $fileUploader->upload($fileData);
                    $filePath = $this->getParameter('app.targetDirectory').'/'.$fileName;
                    // Save data into DB
                    $file = new ResourceFile();
                    $file->setName($fileName);
                    $file->setPath($filePath);
                    $file->setSize($fileSize);
                    $file->setType($fileMimeType);
                    $file->setOwner($this->getUser());
                    $file->setUpdatetime(new DateTimeImmutable());

                    // Save data to the DB.
                    $entityManager->persist($file);
                    $entityManager->flush();

                    $this->addFlash('notice', 'Item has been saved.');
                }
            }

            // Redirect to Settings Page
            // return $this->redirectToRoute('file');
        }

        // IMAGES
        $images = $resourceFileRepository->findFilesByType('image/jpeg', $this->getUser());
        if (!$images) {
            $images = false;
        }

        // OTHER Files
        $otherFiles = $resourceFileRepository->findOtherFilesThen('image%', $this->getUser());
        if (!$otherFiles) {
            $otherFiles = false;
        }

        return $this->render('file/index.html.twig', [
            'files' => $files,
            'form' => $form->createView(),
            'images' => $images,
            'otherFiles' => $otherFiles,
            'error' => false,
        ]);
    }
}
