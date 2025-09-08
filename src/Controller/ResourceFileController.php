<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;

//Entities
use App\Entity\ResourceFile;

//Repositories
use App\Repository\ResourceFileRepository;

//Forms
use App\Form\FileFormType;

class ResourceFileController extends AbstractController
{
    /**
     * @Route("/file", name="file")
     */
    public function index(Request $request, ResourceFileRepository $resourceFileRepository, FileUploader $fileUploader): \Symfony\Component\HttpFoundation\Response
    {
        //Check access
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //NEW Files
        $files = $resourceFileRepository->findBy(['owner' => $this->getUser()], ['updatetime' => 'DESC'], 6);
        if(!$files) {
            $files = FALSE;
        }
        
        $form = $this->createForm(FileFormType::class);
        $form->handleRequest($request);
        //Process form
        if($form->isSubmitted() && $form->isValid()) 
        {
            //Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();

            $newFiles = $request->files->get('file_form')['files'];
            foreach($newFiles as $fileData)
            {
                if($fileData)
                {
                    //Read file data for DB
                    $fileSize = $fileData->getSize();
                    $fileMimeType = $fileData->getMimeType();
                    //Save file into file system
                    $fileName = $fileUploader->upload($fileData);
                    $filePath = $this->getParameter('app.targetDirectory').'/'.$fileName;
                    //Save data into DB
                    $file = new ResourceFile();
                    $file->setName($fileName);
                    $file->setPath($filePath);
                    $file->setSize($fileSize);
                    $file->setType($fileMimeType);
                    $file->setOwner($this->getUser());
                    $file->setUpdatetime(new \DateTimeImmutable());

                    //Save data to the DB.
                    $entityManager->persist($file);
                    $entityManager->flush();

                    $this->addFlash('notice', 'Item has been saved.'); 
                }
            }

            //Redirect to Settings Page 
            //return $this->redirectToRoute('file');            
        }
        
        //IMAGES
        $images = $resourceFileRepository->findFilesByType('image/jpeg', $this->getUser());
        if(!$images) {
            $images = FALSE;
        }

        //OTHER Files
        $otherFiles = $resourceFileRepository->findOtherFilesThen('image%', $this->getUser());
        if(!$otherFiles) {
            $otherFiles = FALSE;
        }

        return $this->render('file/index.html.twig', [
            'files' => $files,
            'form' => $form->createView(),
            'images' => $images,
            'otherFiles' => $otherFiles,
            'error' => FALSE,
        ]);
    }
}
