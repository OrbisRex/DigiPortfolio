<?php

/*
 * The MIT License
 *
 * Copyright 2017 David Ehrlich.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

//Entities
use App\Entity\Descriptor;

//Forms
use App\Form\DescriptorFormType;

//Repositories
use App\Repository\DescriptorRepository;
use App\Repository\CriterionRepository;

/**
 * Description of DescriptorController
 *
 * @author David Ehrlich
 */
class DescriptorController extends BasicController
{
    private $descriptorRepository;
    private $criterionRepository;
    
    public function __construct(DescriptorRepository $descriptorRepository, CriterionRepository $criterionRepository)
    {
        $this->descriptorRepository = $descriptorRepository;
        $this->criterionRepository = $criterionRepository;
    }
    
    #[Route(path: '/descriptor', name: 'descriptor')]
    public function index(): \Symfony\Component\HttpFoundation\Response 
    {
        //Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        //List descriptor
        $descriptors = $this->descriptorRepository->findByPerson($this->getUser());

        if(!$descriptors) {
            throw $this->createNotFoundException(
                'No Descriptor found.'
            );
        }

        return $this->render('descriptor/index.html.twig', [
            'descriptors' => $descriptors,
        ]);
    }

    #[Route(path: '/descriptor/new', name: 'new-descriptor')]
    public function new(Request $request)
    {     

        //Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        //Keep a track with new criterion
        $criterionId = $request->query->get('criterionId');

        if(!$criterionId) {
            throw $this->createNotFoundException(
                'No Criterion ID found.'
            );
        }
        
        //Fetch criterion data
        $criterion = $this->criterionRepository->find($criterionId);
        if(!$criterion) {
            throw $this->createNotFoundException(
                'No Criteria found.'
            );
        }
        
        //Read level for descriptor
        $level = $request->query->get('level');

        $descriptorId = $request->query->get('descriptorId');

        if($descriptorId) {
            $description = $this->descriptorRepository->find($descriptorId)->getDescription();
            $name = $this->descriptorRepository->find($descriptorId)->getName();
        } else {
            $description = null;
            $name = null;
        }
        
        //New Descriptor Form
        $form = $this->createForm(DescriptorFormType::class, null, [
            'level_choice' => $level,
            'descriptor' => $description,
            'name' => $name
        ]);

        $form->handleRequest($request);
        
        //Process Sing In form
        if($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();
            
            //Create new Criteria
            $descriptor = new Descriptor();
            $descriptor->setName($data->getName());
            $descriptor->setDescription($data->getDescription());
            $descriptor->setType($data->getType());
            $descriptor->setWeight($data->getWeight());
            $descriptor->setPerson($this->getUser());
            $descriptor->addCriterion($criterion);
            
            //Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($descriptor);

            //Save data to the DB.
            $entityManager->flush();
            $this->addFlash('success', 'Descriptor has been saved.');
            
            return $this->redirectToRoute('new-descriptor', ['criterionId' => $criterion->getId(), 'descriptorId' => $descriptor->getId(), 'level' => $data->getType()]);            
        }        
        
        return $this->render('descriptor/new.html.twig', array(
            'criterion' => $criterion,
            'form' => $form->createView(),
            'error' => FALSE,
        ));
    }

    #[Route(path: '/descriptor/edit/{criterionId}/{id}', name: 'edit-descriptor')]
    public function edit(int $criterionId, int $id, Request $request)
    {     
        //Check access
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        if(!$criterionId && !$id) {
            throw $this->createNotFoundException(
                'No Criterion or Descriptor ID found.'
            );
        }

        //Fetch related criterion
        $criterion = $this->criterionRepository->find($criterionId);

        //Fetch descriptor
        $descriptor = $this->descriptorRepository->find($id);

        //Descriptor Form
        $form = $this->createForm(DescriptorFormType::class, $descriptor, [
            'name' => $descriptor->getName(),
            'descriptor' => $descriptor->getDescription(),
            'level_choice' => $descriptor->getType(),
        ]);
        $form->handleRequest($request);
        
        //Process Sing In form
        if($form->isSubmitted() && $form->isValid()) 
        {
            $data = $form->getData();
            
            $descriptor->setName($data->getName());
            $descriptor->setDescription($data->getDescription());
            $descriptor->setType($data->getType());
            $descriptor->setWeight($data->getWeight());
            $descriptor->setPerson($this->getUser());
            //$descriptor->addCriteria($criteria);
            
            //Doctrine Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($descriptor);

            //Save data to the DB.
            $entityManager->flush();
            
            return $this->redirectToRoute('edit-descriptor', array('criterionId' => $criterionId, 'id' => $id));
        }        
        
        return $this->render('descriptor/edit.html.twig', array(
            'criterion' => $criterion,
            'descriptor' => $descriptor,
            'form' => $form->createView(),
            'error' => FALSE,
        ));
    }
    
}
