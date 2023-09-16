<?php 

namespace App\Models;

use CodeIgniter\Model;

class ModelBarang extends Model
{
    protected $table = 'tbl_biaya';
    protected $primaryKey = 'id';
    protected $allowedFields  = [
        'jenis_barang', 'harga_barang'
    ];
}