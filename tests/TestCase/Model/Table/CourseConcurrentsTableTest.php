<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CourseConcurrentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CourseConcurrentsTable Test Case
 */
class CourseConcurrentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\CourseConcurrentsTable
     */
    public $CourseConcurrents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.course_concurrents',
        'app.courses',
        'app.concurrents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CourseConcurrents') ? [] : ['className' => 'App\Model\Table\CourseConcurrentsTable'];
        $this->CourseConcurrents = TableRegistry::get('CourseConcurrents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CourseConcurrents);

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
