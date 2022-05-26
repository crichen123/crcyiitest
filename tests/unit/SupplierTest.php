<?php

use app\service\Supplierservice;

class SupplierTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testFindSupplierById()
    {
        expect_that($supplier = Supplierservice::find()->where(['id'=>12])->one());
        expect($supplier->name)->equals('supplier12');

        expect_not(Supplierservice::find()->where(['id'=>999])->one());
    }

    public function testFindSupplierByUsername()
    {
        expect_that($supplier = Supplierservice::find()->where(['name'=>'supplier12'])->one());
        expect_not(Supplierservice::find()->where(['name'=>'123supplier'])->one());
    }

    /**
     * @depends testFindSupplierByUsername
     */
    public function testValidateSupplier($supplier)
    {
        $supplier = Supplierservice::find()->where(['name'=>'supplier12'])->one();
        expect_that($supplier->validate('name'));
        $supplier->setAttribute('name','--supplier');
        expect_not($supplier->validate('name'));

        expect_that($supplier->validate('code'));
        $supplier->setAttribute('code','--code');
        expect_not($supplier->validate('code'));
    }

    public function testAttributeSupplierByNequal()
    {
        $supplier = Supplierservice::find()->where(['name'=>'supplier12'])->one();
        $supplier->nequal = '=';
        expect_that($supplier->getAttribute('id'));
    }


}