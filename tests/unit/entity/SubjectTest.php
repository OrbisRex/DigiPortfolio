<?php

namespace App\Tests\Unit\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Person;
use App\Entity\Subject;

class SubjectTest extends KernelTestCase
{
    private $person;
    private $subject;

    public function setup(): void
    {
        $this->person = new Person();
        $this->person->setName('Admin');
        $this->person->setPassword('Hello');
        $this->person->setEmail('admin@myemail.com');
        $this->person->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $this->subject = new Subject();
        $this->subject->setName('English');
        $this->subject->addPerson($this->person);
        $this->subject->setLog(null);

    }

    public function testGetAndSetData(): void
    {
        self::assertSame('English', $this->subject->getName());
        self::assertSame(null, $this->subject->getLog());
    }
}