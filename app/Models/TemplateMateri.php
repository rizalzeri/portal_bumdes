<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateMateri extends Model
{
    use HasFactory;

    protected $table = 'materi_templates';
    protected $guarded = ['id'];
}
