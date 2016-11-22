<?php

namespace App\Modules\Jukebox\Http\Requests;

use App\Http\Requests\Request;
use App\Modules\Jukebox\Models\BadWord;

class MusicRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->session()->has('jukebox.user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        \Validator::extend('badword', function($attribute, $value, $parameters) {
            return !BadWord::where('word', 'LIKE', "%{$value}%")->exists();
        });

        return [
            'receiver' => 'badword',
            'message' => 'badword'
        ];
    }
}
