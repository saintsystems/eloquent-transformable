<?php

namespace SaintSystems\Eloquent\Transformable\Tests;

use SaintSystems\Eloquent\Transformable\Transformable;

class TransformableTest extends TestCase
{

    /** @test */
    public function test_actual_database_model()
    {
        $actualModel = new ActualDatabaseModel([
            'PK_Database_ID' => 1,
            'DB_Name' => 'Name',
            'DB_Description' => 'Description',
            'FK_Foreign_Key_ID' => 2
        ]);

        //$this->addWarning('$actualModel->PK_Database_ID:'.$actualModel->PK_Database_ID);
        $this->assertEquals($actualModel->PK_Database_ID, 1);
        $this->assertEquals($actualModel->DB_Name, 'Name');
        $this->assertEquals($actualModel->DB_Description, 'Description');
        $this->assertEquals($actualModel->FK_Foreign_Key_ID, 2);
    }

    /** @test */
    public function test_desired_database_model()
    {
        $actualModel = new DesiredDatabaseModel([
            'id' => 1,
            'name' => 'Name',
            'description' => 'Description',
            'foreign_key_id' => 2
        ]);

        //$this->addWarning('$actualModel->PK_Database_ID:'.$actualModel->PK_Database_ID);
        $this->assertEquals($actualModel->id, 1);
        $this->assertEquals($actualModel->name, 'Name');
        $this->assertEquals($actualModel->description, 'Description');
        $this->assertEquals($actualModel->foreign_key_id, 2);
    }

    /** @test */
    public function test_desired_database_model_find()
    {
        $desiredBuilder = DesiredDatabaseModel::whereKey(1);

        $expected = 'select * from "tbl_Database_Table" where "tbl_Database_Table"."PK_Database_ID" = ?';
        $actual = $desiredBuilder->toSql();

        // $this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_where_id()
    {
        $desiredBuilder = DesiredDatabaseModel::where('id', 1);

        $expected = 'select * from "tbl_Database_Table" where "tbl_Database_Table"."PK_Database_ID" = ?';
        $actual = $desiredBuilder->toSql();

        //$this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_where_id_magic_method()
    {
        $desiredBuilder = DesiredDatabaseModel::whereId(1);

        $expected = 'select * from "tbl_Database_Table" where "tbl_Database_Table"."PK_Database_ID" = ?';
        $actual = $desiredBuilder->toSql();

        //$this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_compound_where()
    {
        $desiredBuilder = DesiredDatabaseModel::where('id', 1)->orWhere('name', 'Name');

        $expected = 'select * from "tbl_Database_Table" where "tbl_Database_Table"."PK_Database_ID" = ? or "tbl_Database_Table"."DB_Name" = ?';
        $actual = $desiredBuilder->toSql();

        // $this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_compound_where_name_magic_method()
    {
        $desiredBuilder = DesiredDatabaseModel::whereName('Name');

        $expected = 'select * from "tbl_Database_Table" where "tbl_Database_Table"."DB_Name" = ?';
        $actual = $desiredBuilder->toSql();

        // $this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_order_by_name()
    {
        $desiredBuilder = DesiredDatabaseModel::orderBy('name');

        $expected = 'select * from "tbl_Database_Table" order by "tbl_Database_Table"."DB_Name" asc';
        $actual = $desiredBuilder->toSql();

        // $this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_order_by_name_desc()
    {
        $desiredBuilder = DesiredDatabaseModel::orderBy('name','desc');

        $expected = 'select * from "tbl_Database_Table" order by "tbl_Database_Table"."DB_Name" desc';
        $actual = $desiredBuilder->toSql();

        // $this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

    /** @test */
    public function test_desired_database_model_order_by_name_and_id()
    {
        $desiredBuilder = DesiredDatabaseModel::orderBy('name')->orderBy('id');

        $expected = 'select * from "tbl_Database_Table" order by "tbl_Database_Table"."DB_Name" asc, "tbl_Database_Table"."PK_Database_ID" asc';
        $actual = $desiredBuilder->toSql();

        // $this->addWarning($actual);

        $this->assertEquals($expected, $actual);
    }

}
