# DigiPortfolio - AI Coding Assistant Instructions

## Project Overview
DigiPortfolio is a Symfony-based portfolio management system for collecting and evaluating learning evidence. The application follows a structured MVC architecture with Doctrine ORM for database interactions.

## Key Technologies
- PHP 8.1+
- Symfony 6.4
- Doctrine ORM
- PostgreSQL Database
- Webpack Encore for asset management
- Twig templating engine

## Core Architecture

### Authentication & Authorization
- User roles: ROLE_ADMIN, ROLE_TEACHER, ROLE_STUDENT, ROLE_USER
- Security managed via Symfony Security Bundle
- Example: `src/Controller/SecurityController.php` handles login/logout

### Entity Structure
- Core entities in `src/Entity/`:
  - Person: User management with role-based access
  - Assignment: Tasks created by teachers
  - Submission: Student work submissions
  - Criterion/Descriptor: Evaluation criteria system
  - ResourceFile: File upload management

### Controller Patterns
- Controllers extend `AbstractController` or `BasicController`
- Role checks using `denyAccessUnlessGranted()`
- Example structure:
```php
#[Route(path: '/route', name: 'route-name')]
public function actionName(): Response {
    $this->denyAccessUnlessGranted('ROLE_REQUIRED');
    // Controller logic
}
```

## Development Workflow

### Environment Setup
1. Database: Create 'digiportfolio' PostgreSQL database
2. Dependencies:
```powershell
composer install
npm install --legacy-peer-deps
```
3. Start development server:
```powershell
symfony server:start --port 8080
npm run watch # For asset compilation
```

### Testing
- PHPUnit for testing with separate test database
- Setup test database:
```powershell
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:schema:create
```
- Run tests: `php bin/phpunit`

### Code Quality
- Rector for automated refactoring:
```powershell
./vendor/bin/rector
```
- Follow Symfony best practices for controller/service organization
- Use constructor injection for dependencies

## Project-Specific Conventions

### Forms
- Form types in `src/Form/`
- Always extend `AbstractType`
- Use validation constraints for data integrity

### File Uploads
- Managed through VichUploaderBundle
- Upload directory: `public/uploadtmp/`
- Use `FileUploader` service for file handling

### Database Migrations
- Migrations in `migrations/` directory
- Generate new migrations when modifying entities:
```powershell
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

## Common Patterns
1. Entity Repository pattern for database queries
2. Service layer for business logic
3. Twig templates with base layout inheritance
4. CSRF protection in forms
5. Role-based access control in controllers