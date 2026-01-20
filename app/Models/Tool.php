<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Vcoder7\Ltools\Http\Traits\RecordChangesTrait;

class Tool extends Model
{
    use HasUuids, RecordChangesTrait;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'category',
        'description',
        'website',
    ];
}
