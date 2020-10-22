<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchIssue extends Controller
{
    public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'q' => ['required','string','max:255'],
            'order' => ['regex:/(desc|asc)$/i', 'nullable'],
            'sort' => ['regex:/(comments|reactions|reactions-+1|reactions--1|reactions-smile|reactions-thinking_face|reactions-heart|reactions-tada|interactions|created|updated)$/i', 'nullable'],
            'label' => ['regex:/(bug|documentation|duplicate|enhancement|goodfirstissue|helpwanted|invalid|question|wontfix)$/i','string', 'nullable'],
            'language' => ['nullable','string'],
            'state' => ['regex:/(open|close)$/i','nullable','string'],
        ]);
        if($validator->fails()) return $validator->errors();

        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        if(!empty($request->order)) $request->order = '&order='.$request->order;
        if(!empty($request->sort)) $request->sort = '&sort='.$request->sort;
        if(!empty($request->label)) $request->label = '+label:'.$request->label;
        if(!empty($request->state)) $request->state = '+state:'.$request->state;
        if(!empty($request->language)) $request->language = '+language:'.$request->language;
        
        return Http::get('https://api.github.com/search/issues?q='.$request->q.$request->label.$request->state.$request->language.$request->order.$request->sort)->json();
    }
}
