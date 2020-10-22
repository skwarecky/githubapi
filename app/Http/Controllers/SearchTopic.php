<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchTopic extends Controller
{
    public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'q' => ['required','string','max:255'],
        ]);
        if($validator->fails()) return $validator->errors();

        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        
        return  Http::withHeaders([
                    'accept' => 'application/vnd.github.mercy-preview+json'
                ])->get('https://api.github.com/search/topics?q='.$request->q)->json();
    }
}
