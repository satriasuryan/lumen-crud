<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Uker extends Model
{
    protected $table = "ukers";

    protected $fillable = [
        'id', 'name', 'user_id', 'kanwil_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Define an inverse one-to-many relationship with App\Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kanwil()
    {
        return $this->belongsTo(Kanwil::class);
    }
}
