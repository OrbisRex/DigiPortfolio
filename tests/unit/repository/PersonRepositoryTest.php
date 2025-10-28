<?php

namespace App\Tests\Unit\Repository;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Doctrine\Persistence\ManagerRegistry;

class PersonRepositoryTest extends KernelTestCase
{
    private $personRepository;

    public function testFindAllStudents(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $registry = $container->get(ManagerRegistry::class);
        $this->personRepository = new PersonRepository($registry);
        $queryResult = $this->personRepository->findAllStudents();
        
        $this->assertIsArray($queryResult, 'findAllStudents should return an array.');
        
        foreach ($queryResult as $student) {
            $this->assertArrayHasKey('roles', $student, 'Each student record should have a roles field.');
            $this->assertStringContainsString('ROLE_STUDENT', $student['roles'], 'Each student should have ROLE_STUDENT in their roles.');
        }
    }
}
