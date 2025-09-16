<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PersonTest extends KernelTestCase
{
    private $person;

    public function setup(): void
    {
        $this->person = new Person();
        $this->person->setName('Admin');
        $this->person->setEmail('admin@myemail.com');
        $this->person->setPassword('Hello');
        $this->person->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
    }

    public function testGetAndSetData(): void
    {
        self::assertSame('admin@myemail.com', $this->person->getUserIdentifier());
        self::assertSame('Admin', $this->person->getName());
        self::assertSame('admin@myemail.com', $this->person->getEmail());
        self::assertSame('Hello', $this->person->getPassword());
        self::assertContains(
            'ROLE_USER',
            $this->person->getRoles(),
            'Every user must have ROLE_USER.'
        );
    }
}
