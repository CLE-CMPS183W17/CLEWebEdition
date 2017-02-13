<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CoursePrerequisitesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CoursePrerequisitesTable Test Case
 */
class CoursePrerequisitesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CoursePrerequisitesTable
     */
    public $CoursePrerequisites;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.course_prerequisites',
        'app.courses',
        'app.prerequisites'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CoursePrerequisites') ? [] : ['className' => 'App\Model\Table\CoursePrerequisitesTable'];
        $this->CoursePrerequisites = TableRegistry::get('CoursePrerequisites', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CoursePrerequisites);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
