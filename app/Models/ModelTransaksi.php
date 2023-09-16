<?php 

namespace App\Models;

use CodeIgniter\Model;

class ModelTransaksi extends Model
{
    protected $table = 'tbl_transaksi';
    protected $primaryKey = 'id';
    protected $allowedFields  = [
        'id_user', 'id_biaya', 'jumlah_barang', 'total', 'tanggal', 'no_nota, bayaran'
    ];
}