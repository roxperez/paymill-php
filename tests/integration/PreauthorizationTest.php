<?php

namespace Paymill\Test\Integration;

use Paymill\API\Curl;
use Paymill\Models as Models;
use Paymill\Request;
use PHPUnit_Framework_TestCase;

/**
 * PreauthorizationTest
 */
class PreauthorizationTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Paymill\Services\Request
     */
    private $_service;

    /**
     * @var \Paymill\Models\Request\Preauthorization
     */
    private $_model;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->_service = new Request();
        $this->_service->setConnectionClass(new Curl(API_TEST_KEY));
        $this->_model = new Models\Request\Preauthorization();
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->_service = null;
        $this->_model = null;
        parent::tearDown();
    }

    /**
     * @test
     * @codeCoverageIgnore
     */
    public function createPreauthorization()
    {
        $this->_model->setToken("098f6bcd4621d373cade4e832627b4f6")
            ->setAmount(100)
            ->setCurrency('EUR');
        $result = $this->_service->create($this->_model);
        $this->assertInstanceOf('Paymill\Models\Response\Preauthorization', $result);
        return $result;
    }

    /**
     * @test
     * @codeCoverageIgnore
     * @depends createPreauthorization
     */
    public function updatePreauthorization($model)
    {
        $this->_model->setId($model->getId());
        $this->markTestIncomplete(
            'Preauthorization should return a valid Object like Client "Method not found". Returns a empty 500-Response instead.'
        );

        $result = $this->_service->update($this->_model);

        $this->assertInstanceOf('Paymill\Models\Response\Error', $result, var_export($result, true));
        $this->assertEquals('Preauthorization was not found', $result->getErrorMessage());
    }

    /**
     * @test
     * @codeCoverageIgnore
     * @depends createPreauthorization
     */
    public function getOnePreauthorization($model)
    {
        $this->_model->setId($model->getId());
        $this->assertInstanceOf('Paymill\Models\Response\Preauthorization', $result = $this->_service->getOne($this->_model), var_export($result, true));
        $this->assertEquals($model->getId(), $result->getId());
    }

    /**
     * @test
     * @codeCoverageIgnore
     * @depends createPreauthorization
     */
    public function getAllPreauthorization()
    {
        $this->_model;
        $result = $this->_service->getAll($this->_model);
        $this->assertInternalType('array', $result, var_export($result, true));
    }

    /**
     * @test
     * @codeCoverageIgnore
     */
    public function getAllPreauthorizationWithFilter()
    {
        $this->_model->setFilter(array(
            'count' => 2,
            'offset' => 0
            )
        );
        $result = $this->_service->getAll($this->_model);
        $this->assertEquals(2, count($result), var_export($result, true));
    }

    /**
     * @test
     * @codeCoverageIgnore
     * @depends createPreauthorization
     * @depends getOnePreauthorization
     * @depends updatePreauthorization
     */
    public function deletePreauthorization($model)
    {
        $this->_model->setId($model->getId());
        $result = $this->_service->delete($this->_model);
        $this->assertInternalType('array', $result, var_export($result, true));
    }

}
