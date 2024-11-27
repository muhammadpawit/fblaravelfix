<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPO extends Model
{
    use HasFactory;
    protected $table='produksi_po';
    protected $guarded=['id_produksi_po'];
}
