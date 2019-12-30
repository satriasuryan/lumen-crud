<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kanwil extends Model
{
    protected $table = "kanwils";

    protected $fillable = [
        'id', 'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Define a one-to-many relationship with App\Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ukers()
    {
        return $this->hasMany(Uker::class, 'kanwil_id', 'id');
    }
}
