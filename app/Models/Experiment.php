<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public static $config_table = [
        ['title' => 'No',   'data'  => 'DT_RowIndex', 'name' => 'id', 'searchable' => false, 'className' => 'dt-no text-center'],
        ['title' => 'Nama', 'data'  => 'name',        'name' => 'name'],
        ['title' => 'Agama', 'data' => 'religion',    'name' => 'religion'],
        ['title' => 'Nilai', 'data' => 'score',       'name' => 'score'],
        ['title' => 'Status', 'data' => 'status',     'name' => 'is_active'],
    ];
}
