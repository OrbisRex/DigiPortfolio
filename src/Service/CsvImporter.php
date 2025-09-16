<?php

namespace App\Service;

use Symfony\Component\Config\Definition\Exception\Exception;

class CsvImporter
{
    private $data;
    private $rowNumber;

    public function __construct(private $targetDirectory)
    {
        $this->data = [];
        $this->rowNumber = 0;
    }

    public function userImport($csvFileName)
    {
        if (($file = fopen($this->targetDirectory.'/'.$csvFileName, 'r')) !== false) {
            $header = $row = fgetcsv($file, 1000, ',');
            while (($row = fgetcsv($file, 1000, ',')) !== false) {
                // Row has less items than heading.
                if (count($header) > count($row)) {
                    array_push($row, null);
                }

                foreach ($header as $column => $heading) {
                    $row_new[strtolower($heading)] = $row[$column];
                }
                $this->data[] = $row_new;
                ++$this->rowNumber;
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
        foreach ($this->data as $line) {
            if (!filter_var($line['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Invalid email format '.$line['email']);
            }
        }
    }

    /*
     * Generate new password if column password or password value does not exist.
     */
    private function generatePassword()
    {
        foreach ($this->data as $index => $line) {
            if (!array_key_exists('password', $line)) {
                $line += ['password' => uniqid()];
                $this->data[$index] = $line;
            } elseif ($line['password'] == '') {
                $line['password'] = uniqid();
                $this->data[$index] = $line;
            }
        }
    }
}
