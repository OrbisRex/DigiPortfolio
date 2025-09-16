<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Person;
use App\Entity\Set;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SetTest extends KernelTestCase
{
    private $person;
    private $set;

    public function setup(): void
    {
        $this->person = new Person();
        $this->person->setName('Jane');
        $this->person->setPassword('Hello');
        $this->person->setEmail('jane@myemail.com');
        $this->person->setRoles(['ROLE_USER', 'ROLE_STUDENT']);

        $this->set = new Set();
        $this->set->setName('Year 6');
        $this->set->setType('Year group');
        $this->set->addPerson($this->person);
        $this->set->setLog(null);
    }

    public function testGetAndSetData(): void
    {
        self::assertSame('Year 6', $this->set->getName());
        self::assertInstanceOf(Person::class, $this->set->getPeople()->first());
        self::assertContains(
            'ROLE_STUDENT',
            $this->set->getPeople()->first()->getRoles(),
            'User for subject must be at least ROLE_STUDENT.'
        );
        self::assertSame(null, $this->set->getLog());
    }
}
