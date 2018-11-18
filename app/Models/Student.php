<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{   
    protected $fillable = ['name', 'birth', 'genre', 'room_id'];

    // protected $casts = ['birth' => 'date:d/m/Y'];

    // protected $hidden = ['created_at', 'updated_at'];

    // protected $visible = ['name', 'birth', 'genre', 'room_id', 'is_accepted'];

    // protected $appends = ['is_accepted']; //atributos virtuais

    /**
     * Mapeamento entre aluno e sala de aula
     *
     * @return void
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Novo atributo virtual
     *
     * @return void
     */
    // public function getIsAcceptedAttribute()
    // {
    //     return $this->attributes['birth'] > '2000-01-01' ? 'aceito' : 'nao foi aceito';
    // }
}
