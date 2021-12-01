<?php

namespace App\Models;

use Based\Fluent\Fluent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    use Fluent, HasFactory;

    public string $name, $type;
    public int $battery;
    public float $lat, $lon;

    protected $fillable = [
        'name',
        'type',
        'battery',
        'lat',
        'lon'
    ];
}
