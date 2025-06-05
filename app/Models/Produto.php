<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    protected $dates = ['data_vencimento', 'data_cadastro'];

    protected $fillable = [
        'codigo_ean',
        'nome',
        'descricao',
        'fabricante',
        'ano_fabricacao',
        'data_vencimento',
        'valor',
        'data_cadastro',
        'imagem',
    ];

    protected $casts = [
        'data_vencimento' => 'datetime',
        'data_cadastro' => 'datetime',
    ];

    public function eanScans()
    {
        return $this->hasMany(EanScan::class);
    }
}
