<?php

namespace SaintSystems\Eloquent\Transformable\Tests;

class ActualDatabaseModel extends Model
{
    protected $table = 'tbl_Database_Table';

    protected $primaryKey = 'PK_Database_ID';

    protected $appends = [
        'DB_Name',
        'DB_Description',
        'FK_Foreign_Key_ID'
    ];

    protected $guarded = [];
}

