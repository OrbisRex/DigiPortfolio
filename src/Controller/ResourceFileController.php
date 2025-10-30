<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;

use App\Entity\ResourceFile;
use App\Form\FileFormType;
use App\Repository\ResourceFileRepository;
use App\Service\FileUploader;

class ResourceFileController extends AbstractController
{
    #[Route(path: '/file', name: 'file')]
    public function index(
        Request $request,
        ResourceFileRepository $resourceFileRepository,
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // NEW Files
        $files = $resourceFileRepository->findBy(['owner' => $this->getUser()], ['updatetime' => 'DESC'], 6);
        if (!$files) {
            $files = false;
        }

        // IMAGES
        $images = $resourceFileRepository->findFilesByType('image%', $this->getUser());
        if (!$images) {
            $images = false;
        }

        // CSV Files
        $csvFiles = $resourceFileRepository->findFilesByType('text/csv', $this->getUser());
        if (!$csvFiles) {
            $csvFiles = false;
        }


        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);
        // Process form
        if ($form->isSubmitted() && $form->isValid()) {
            $newFiles = $form->get('files')->getData();
            $fileCount = 0;

            foreach ($newFiles as $fileData) {
                if ($fileData) {
                    // Get file data before save
                    $fileSize = $fileData->getSize();
                    $fileType = $fileData->getMimeType();

                    // Save file into file system
                    $fileName = $fileUploader->upload($fileData);                    
                    $filePath = $this->getParameter('app.targetDirectory') . '/' . $fileName;

                    // Save data into DB
                    $file = new ResourceFile();
                    $file->setName($fileName);
                    $file->setPath($filePath);
                    $file->setSize($fileSize);
                    $file->setType($fileType);
                    $file->setOwner($this->getUser());
                    $file->setUpdatetime(new DateTimeImmutable());

                    // Save data to the DB.
                    $entityManager->persist($file);
                    $entityManager->flush();

                    ++$fileCount;
                }

                $this->addFlash('success', ($fileCount == 1) ? "$fileCount file has been saved." : "$fileCount files have been saved.");
            }

            // Redirect to Settings Page
            return $this->redirectToRoute('file');
        }

        return $this->render('file/index.html.twig', [
            'files' => $files,
            'form' => $form->createView(),
            'images' => $images,
            'csvFiles' => $csvFiles,
            'error' => false,
        ]);
    }
}
