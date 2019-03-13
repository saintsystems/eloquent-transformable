<?php

namespace SaintSystems\Eloquent\Transformable\Tests;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use SaintSystems\Eloquent\Transformable\Transformable;

class Model extends EloquentModel
{
    use Transformable;
}
