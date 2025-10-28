<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Person;
use App\Repository\AssignmentPersonRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\Persistence\ManagerRegistry;

class AssignmentPersonRepositoryTest extends KernelTestCase
{
    private $assignmentPersonRepository;
    private $person;

    public function setUp(): void
    {
        $this->person = new Person();
        $this->person->setName('Admin');
        $this->person->setEmail('admin@myemail.com');
        $this->person->setPassword('Hello');
        $this->person->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
    }

    public function testFindLastAssignments(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $registry = $container->get(ManagerRegistry::class);
        $this->assignmentPersonRepository = new AssignmentPersonRepository($registry);
        $queryResult = $this->assignmentPersonRepository->findLastAssignments($this->person, 5);
        
        $this->assertIsArray($queryResult, 'findLastAssignments should return an array.');
        
        foreach ($queryResult as $assignment) {
            $this->assertArrayHasKey('id', $assignment, 'Each assignment should have a id field.');
            $this->assertArrayHasKey('name', $assignment, 'Each assignment should have a name field.');
        }
    }
}
