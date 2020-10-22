<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchCommit extends Controller
{
    public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'q' => ['required','string','max:255'],
            'order' => ['regex:/(desc|asc)$/i', 'nullable','string'],
            'sort' => ['regex:/(author-date|committer-date)$/i', 'nullable','string'],
            'language' => ['nullable','string'],
        ]);
        if($validator->fails()) return $validator->errors();

        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        if(!empty($request->language)) $request->language = '+language:'.$request->language;
        if(!empty($request->order)) $request->order = '&order='.$request->order;
        if(!empty($request->sort)) $request->sort = '&sort='.$request->sort;
        return  Http::withHeaders([
                    'accept' => 'application/vnd.github.cloak-preview'
                ])->get('https://api.github.com/search/commits?q='.$request->q.$request->language.$request->order.$request->sort)->json();
    }
}
