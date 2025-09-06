<?php

/*
 * The MIT License
 *
 * Copyright 2017 David Yilma.
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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of BasicController
 *
 * @author David Yilma
 */
class BasicController extends AbstractController {
    
    
    protected function findById($em, $entity, $id)
    {
        //Fetch data
        $data = $em->getRepository($entity)->find($id);

        if(!$data) {
            throw $this->createNotFoundException(
                'No data found for '.$entity.'.'
            );
        }
        
        return $data;
    }
    
    /**
     * Create a list of choices
     * @param string $entityRepository
     * @return array
     * @throws type
     */
    protected function createChoiceList($entityRepository, $search = FALSE)
    {
        if($search) {
            //Fetch selected data
            $items = $entityRepository->findBy($search);
        } else {
            //Fetch All data
            $items = $entityRepository->findAll();
        }

        if(!$items) {
            $choiceList = array('Unspecified' => 0);
        }
        
        foreach($items as $item) {
            $choiceList[$item->getName()] = $item->getId();
        }

        return $choiceList;
    }
    
    /*Deprecated*/
    protected function readSession(Request $request, $parameter)
    {
        //Cookies
        $userSession = ($request->cookies->has($parameter)) ? $request->cookies->get($parameter) : FALSE;
        
        //GET
        if(!$userSession)
        {
            $userSession = ($request->query->has($parameter)) ? $request->query->get($parameter) : FALSE ;
        }
        
        //SESSION
        if(!$userSession)
        {
            $userSession = ($request->getSession()->has($parameter)) ? $request->getSession()->get($parameter) : FALSE ;
        }
        
        return $userSession;
    }
    
    protected function readUserCookie(Request $request)
    {
        
        if($request->cookies->has('user'))
        {
            return $request->cookies->get('user');
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Find user in DB
     * @param string $username
     * @param string $password
     * @param string $token
     * @return User or NULL
     */
    protected function findUser($username, $password, $token = NULL)
    {
        $user = $this->getDoctrine()->getRepository('App:User')->findOneBy(
                array('username' => $username, 'password' => $password)
        );
        
        return $user;
    }
    
    protected function isUserOnline($token)
    {
        if($token) 
        {
            $user = $this->getDoctrine()->getRepository('App:User')->findOneBy(
                    array('token' => $token)
            );
            
            return ($user) ? $user : FALSE;
        } 
        else
        {
            return FALSE;
        }
    }
    
    protected function secondsToString($timeTotal)
    {
        $hrs = floor($timeTotal / 3600);
        $min = floor($timeTotal / 60 % 60);
        $sec = floor($timeTotal % 60);
        $hoursTotal = sprintf('%02d:%02d:%02d', $hrs, $min, $sec);
        
        return $hoursTotal;        
    }
}
