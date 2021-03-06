<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function answers()
    {
        return $this->hasMany(Question_answer::class)->orderBy('id','DESC');
    }

    public function categories($question)
    {
        $adviser_category_id=$question->question_category_id;
        return Adviser_category::find($adviser_category_id);
    }

    public function user($question)
    {
        return User::find($question->user_id)->select('name','avatar')->get();

    }
}
