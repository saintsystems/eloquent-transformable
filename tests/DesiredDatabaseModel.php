<?php

namespace SaintSystems\Eloquent\Transformable\Tests;

class DesiredDatabaseModel extends ActualDatabaseModel
{
    // Desired $primaryKey name (PK_Database_ID is the actual PK in the database)
    protected $primaryKey = 'id';

    /**
     * Transformation Mapping of DB Column Names to desired Eloquent Model Attribute Names
     * This variable comes from the SaintSystems\Eloquent\Transformable\Transformable Trait
     * used in the base Model.php
     * @var array
     */
    protected $transform = [
        'id' => 'PK_Database_ID',
        'name' => 'DB_Name',
        'description' => 'DB_Description',
        'foreign_key_id' => 'FK_DB_Foreign_Key_ID'
    ]; // TransformationMap;
}

