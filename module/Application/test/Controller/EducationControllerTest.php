<?php
namespace ApplicationTest\Controller;

use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class EducationControllerTest extends AbstractHttpControllerTestCase
{
    protected function setUp(): void
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testGetEducationList()
    {
        $this->dispatch('/api/education', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('application\\controller\\educationcontroller');
        $this->assertMatchedRouteName('api-education');
    }
} 