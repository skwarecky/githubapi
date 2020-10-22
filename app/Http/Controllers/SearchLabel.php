<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SearchLabel extends Controller
{
    public function search(Request $request){
        $validator  = Validator::make($request->all(), [
            'repositoryid' => ['required', 'integer'],
            'q' => ['required','string','max:255','nullable'],
            'order' => ['regex:/(desc|asc)$/i', 'nullable'],
            'sort' => ['regex:/(created|updated)$/i', 'nullable'],
        ]);
        if($validator->fails()) return $validator->errors();

        if(!empty($request->repositoryid)) $request->repositoryid = 'repository_id='.$request->repositoryid;
        if(preg_match('/\s+/',$request->q)) $request->q = preg_replace('/\s+/','+', $request->q);
        if(!empty($request->q)) $request->q = '&q='.$request->q;
        if(!empty($request->order)) $request->order = '&order='.$request->order;
        if(!empty($request->sort)) $request->sort = '&sort='.$request->sort;
        
        return Http::get('https://api.github.com/search/labels?'.$request->repositoryid.$request->q.$request->order.$request->sort)->json();
    }
}
