<?php
namespace ApplicationTest\Controller;

use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UserControllerTest extends AbstractHttpControllerTestCase
{
    protected function setUp(): void
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testGetUserList()
    {
        $this->dispatch('/api/user', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Application');
        $this->assertControllerName('application\\controller\\usercontroller');
        $this->assertMatchedRouteName('api-user');
    }

    public function testSortByNameAsc()
    {
        $this->dispatch('/api/user?sort=name&order=ASC', 'GET');
        $this->assertResponseStatusCode(200);
        $data = json_decode($this->getResponse()->getContent(), true);
        $names = array_column($data, 'name');
        $sorted = $names;
        sort($sorted);
        $this->assertEquals($sorted, $names);
    }

    public function testSortByAgeDesc()
    {
        $this->dispatch('/api/user?sort=age&order=DESC', 'GET');
        $this->assertResponseStatusCode(200);
        $data = json_decode($this->getResponse()->getContent(), true);
        $ages = array_column($data, 'age');
        $sorted = $ages;
        rsort($sorted);
        $this->assertEquals($sorted, $ages);
    }

    public function testFilterByName()
    {
        $this->dispatch('/api/user?filter_name=Jan', 'GET');
        $this->assertResponseStatusCode(200);
        $data = json_decode($this->getResponse()->getContent(), true);
        foreach ($data['data'] as $user) {
            $this->assertStringContainsString('Jan', $user['name']);
        }
    }

    public function testFilterByAge()
    {
        $this->dispatch('/api/user?filter_age=30', 'GET');
        $this->assertResponseStatusCode(200);
        $data = json_decode($this->getResponse()->getContent(), true);
        foreach ($data['data'] as $user) {
            $this->assertEquals(30, $user['age']);
        }
    }

    public function testPaginationLimit()
    {
        $this->dispatch('/api/user?limit=2', 'GET');
        $this->assertResponseStatusCode(200);
        $data = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals(2, $data['limit']);
        $this->assertLessThanOrEqual(2, $data['count']);
        $this->assertIsArray($data['data']);
    }

    public function testPaginationPage()
    {
        $this->dispatch('/api/user?page=2&limit=1', 'GET');
        $this->assertResponseStatusCode(200);
        $data = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals(2, $data['page']);
        $this->assertEquals(1, $data['limit']);
        $this->assertIsArray($data['data']);
    }

    public function testExportXls()
    {
        $this->dispatch('/api/user/export-xls', 'GET');
        $this->assertResponseStatusCode(200);
        $headers = $this->getResponse()->getHeaders();
        $this->assertTrue($headers->has('Content-Type'));
        $this->assertStringContainsString('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $headers->get('Content-Type')->getFieldValue());
        $this->assertTrue($headers->has('Content-Disposition'));
        $this->assertStringContainsString('attachment; filename="users.xlsx"', $headers->get('Content-Disposition')->getFieldValue());
        $this->assertNotEmpty($this->getResponse()->getContent());
    }

    public function testExportPdf()
    {
        $this->dispatch('/api/user/export-pdf', 'GET');
        $this->assertResponseStatusCode(200);
        $headers = $this->getResponse()->getHeaders();
        $this->assertTrue($headers->has('Content-Type'));
        $this->assertStringContainsString('application/pdf', $headers->get('Content-Type')->getFieldValue());
        $this->assertTrue($headers->has('Content-Disposition'));
        $this->assertStringContainsString('attachment; filename="users.pdf"', $headers->get('Content-Disposition')->getFieldValue());
        $this->assertNotEmpty($this->getResponse()->getContent());
    }
} 