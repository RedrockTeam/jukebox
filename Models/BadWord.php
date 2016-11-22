<?php

namespace App\Modules\Jukebox\Models;

use Illuminate\Database\Eloquent\Model;

class BadWord extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'jukebox';

    /**
     * @inheritDoc
     */
    protected $table = 'bad_words';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'bw_id';

    /**
     * @inheritDoc
     */
    protected $fillable = ['word', 'count'];

    /**
     * 获取违规字列表
     * @return \Illuminate\Database\Eloquent\Collection;
     */
    public static function getWords()
    {
        return self::orderBy('count', 'desc')->get(['word']);
    }
}
