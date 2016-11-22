<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'stats';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'stat_id';

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $casts = ['stat_meta' => 'array'];
}
