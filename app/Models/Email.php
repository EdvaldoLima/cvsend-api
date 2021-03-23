<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cargo',
        'escolaridade',
        'observacao',
        'arquivo',
        'ip',
        'data_envio'
    ];

    public $timestamps = false;

}