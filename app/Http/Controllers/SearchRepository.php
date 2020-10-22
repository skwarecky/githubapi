<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchRepository extends Controller
{
    public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'q' =>      ['required','string','max:255'],
            'order' =>  ['regex:/(desc|asc)$/i', 'nullable','string'],
            'sort' =>   ['regex:/(stars|forks|updated|help-wanted-issues)$/i', 'nullable','string'],
        ]);
        if($validator->fails()) return $validator->errors();

        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        if(!empty($request->order))     $request->order = '&order='.$request->order;
        if(!empty($request->sort))      $request->sort = '&sort='.$request->sort;

        return Http::get('https://api.github.com/search/repositories?q='.$request->q.$request->order.$request->sort.$request->perPage.$request->page)->json();
    }
}
