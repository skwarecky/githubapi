<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchCode extends Controller
{
    public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'q' => ['required','string','max:255'],
            'order' => ['regex:/(desc|asc)$/i', 'nullable'],
            'sort' => ['regex:/(indexed)$/i', 'nullable'],
            'language' => ['nullable','string'],
            'searchType' => ['regex:/(repo|user|org)$/i','required','string'],
            'searchPhrase' => ['string', 'required','string']
        ]);
        if($validator->fails()) return $validator->errors();

        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        if(!empty($request->order)) $request->order = '&order='.$request->order;
        if(!empty($request->sort)) $request->sort = '&sort='.$request->sort;
        if(!empty($request->searchPhrase)) $request->searchType = '+'.$request->searchType.':'.$request->searchPhrase;
        if(!empty($request->language)) $request->language = '+language:'.$request->language;

        return Http::get('https://api.github.com/search/code?q=repo:'.$request->q.$request->language.$request->searchType.$request->order.$request->sort)->json();
    }
}
