<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Person extends Model
{
    use Filterable;

       protected $fillable = [
        'name',
        'cpf',
        'rg',
        'birth',
    ];
}
