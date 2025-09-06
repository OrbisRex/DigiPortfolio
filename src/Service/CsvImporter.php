<?php

/* 
 * The MIT License
 *
 * Copyright 2020 Yskejp.
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

namespace App\Service;

use Symfony\Component\Config\Definition\Exception\Exception;

class CsvImporter
{
    private $targetDirectory;
    private $data;
    private $rowNumber;
    
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
        $this->data = [];
        $this->rowNumber = 0;
    }

    public function userImport($csvFileName)
    {
        if (($file = fopen($this->targetDirectory.'/'.$csvFileName, 'r')) !== FALSE)
        {
            $header = $row = fgetcsv($file, 1000, ',');
            while (($row = fgetcsv($file, 1000, ',')) !== FALSE)
            {
                //Row has less items than heading.
                if(count($header) > count($row)) {
                    array_push($row, NULL);
                }

                foreach ($header as $column => $heading) {
                    $row_new[strtolower($heading)] = $row[$column];
                }
                $this->data[] = $row_new;
                $this->rowNumber++;
            }
        }
        fclose($file);
        
        $this->checkEmail();
        $this->generatePassword();
        
        return $this->data;
    }
    
    /*
     * Check validity of email.
     */
    private function checkEmail()
    {
        foreach($this->data as $line)
        {
            if (!filter_var($line['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception(
                    'Invalid email format '.$line['email']
                );
            }
        }
    }
    
    /*
     * Generate new password if column password or password value does not exist. 
     */
    private function generatePassword()
    {
        foreach($this->data as $index => $line)
        {
            if(!array_key_exists('password', $line)) {
                $line += ['password' => uniqid()];
                $this->data[$index] = $line;
            } elseif ($line['password'] == '') {
                $line['password'] = uniqid();
                $this->data[$index] = $line;
            }
        }        
    }
}