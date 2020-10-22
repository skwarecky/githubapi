<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchUser extends Controller
{
   public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'q' => ['required','string','max:255'],
            'order' => ['regex:/(desc|asc)$/i', 'nullable'],
            'sort' => ['regex:/(followers|repositories|joined)$/i', 'nullable'],
            'repos' => ['integer','nullable'],
            'followers' => ['integer','nullable'],
        ]);
        if($validator->fails()) return $validator->errors();

        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        if(!empty($request->order)) $request->order = '&order='.$request->order;
        if(!empty($request->sort)) $request->sort = '&sort='.$request->sort;
        if(!empty($request->repos)) $request->repos = '+repos:%3E'.$request->repos;
        if(!empty($request->followers)) $request->followers = '+followers:%3E'.$request->followers;
        $response = Http::get('https://api.github.com/search/users?q='.$request->q.$request->repos.$request->followers.$request->order.$request->sort);
        \Log::info('https://api.github.com/search/users?q='.$request->q.$request->repos.$request->followers.$request->order.$request->sort);
        return $response->json();
    }
}
