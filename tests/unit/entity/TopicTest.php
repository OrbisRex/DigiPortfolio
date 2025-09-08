<?php

namespace App\Tests\Unit\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Person;
use App\Entity\Topic;

class TopicTest extends KernelTestCase
{
    private $person;
    private $topic;

    public function setup(): void
    {
        $this->person = new Person();
        $this->person->setName('Jeff');
        $this->person->setPassword('Hello');
        $this->person->setEmail('jeff@myemail.com');
        $this->person->setRoles(['ROLE_USER', 'ROLE_TEACHER']);

        $this->topic = new topic();
        $this->topic->setName('Counting up to 100');
        $this->topic->setDescription('Learn all numbres from 0 to 100.');
        $this->topic->setPerson($this->person);
        $this->topic->setLog(null);
    }

    public function testGetAndSetData(): void
    {
        self::assertSame('Counting up to 100', $this->topic->getName());
        self::assertInstanceOf(Person::class, $this->topic->getPerson());
        self::assertContains(
            'ROLE_TEACHER', 
            $this->topic->getPerson()->getRoles(), 
            'Author of a topic must be at least ROLE_TEACHER.'
        );
        self::assertSame(null, $this->topic->getLog());
    }
}