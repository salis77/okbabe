<?php

namespace App\Http\Controllers\API;

use App\Adviser;
use App\Adviser_category;
use App\Http\Controllers\Controller;
use App\Question;
use App\User;
use Illuminate\Http\Request;
use Validator;
class SearchController extends Controller
{
    public $successStatus = 200;

    public function universal_search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $all=array();

        //advisers
        $adviser_show=array();
        $advisers=User::where('is_adviser',1)->where('name', 'like', '%'.$request->q.'%')->get();
        $c=0;
        foreach ($advisers as $adviser){
            if ($c<4) {
                $save['info'] = $adviser;
                $save['info']['additional'] = $adviser->adviser($adviser);
                array_push($adviser_show, $save);
                $c++;
            }
        }
        $all['advisers']=$adviser_show;

        //categories
        $categories_show=Adviser_category::where('name', 'like', '%'.$request->q.'%')->select('id', 'name','parent_category_id')->limit(4)->get();
        $all['categories']=$categories_show;


        //questions
        $questions_show=array();
        $questions=Question::where('status',1)->where('subject', 'like', '%'.$request->q.'%')->get();
        $c=0;
        foreach ($questions as $question){
            if ($c<4) {
                $save['info'] = $question;
                $save['info']['category'] = $question->categories($question);
                array_push($questions_show, $save);
            }
        }
        $all['questions']=$questions_show;



        return response()->json(['success' => $all], $this-> successStatus);


    }
}
