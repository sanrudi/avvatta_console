<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvErosNowsPrefer;
use App\Models\AvErosNows;

class CMSController extends Controller
{
    public function index(Request $request)
    {
        $category = "Action";
        $category = ($request->category=="")?$category:$request->category;
        $data = AvErosNowsPrefer::with('erosnow_data')
        ->where('category', '=' , $category)
        ->orderBy('prefer','DESC')->get();
        return view('cms-editor-eros.index',compact('data','category'));
    }

    public function store(Request $request)
    {
        $max = 100;
        $prefer = $max;
        $removedMovies = ($request['removed-movie-cards'])?explode("|", $request['removed-movie-cards']):[];
        $category = $request['card-category'];
        $movieCards = ($request['latest-movie-cards'])?explode("|", $request['latest-movie-cards']):[];
        $latestMovieCards = [];
        foreach($movieCards as $key => $movieCard){
            $prefer = $max - $key;
            $latestMovieCards[] = array('prefer_content_id'=>$movieCard,'prefer'=>$prefer,'category'=>$category);
        }
        if(count($removedMovies) > 0){
            AvErosNowsPrefer::where('category', '=' , $category)
            ->delete();
        }
        if(count($latestMovieCards) > 0){
            AvErosNowsPrefer::upsert($latestMovieCards, ['prefer_content_id', 'category'], ['prefer']);
        }
        return redirect()->route('cms-editor-erosnow.index',['category'=>$category])->with('success','Content Applied successfully.');
    }

    public function searchErosnow(Request $request)
    {
        $search = $request->search;
        $category = "Action";
        $category = ($request->category=="")?$category:$request->category;
        if($search == ''){
           $autocomplate = AvErosNows::orderby('title','asc')
           ->select('content_id','title','small_url','duration','release_year','categories')
           ->where('categories', '=', $category)
           ->limit(5)->get();
        }else{
           $autocomplate = AvErosNows::orderby('title','asc')
           ->select('content_id','title','small_url','duration','release_year','categories')
           ->where('title', 'like', '%' .$search . '%')
           ->where('categories', '=', $category)
           ->limit(5)->get();
        }
        $response = array();
        foreach($autocomplate as $autocomplate){
            $label = "";$label = $autocomplate->title." (".$autocomplate->duration." sec) ".$autocomplate->release_year." ".$autocomplate->categories." ";
           $response[] = array(
               "value"=>$autocomplate->content_id,
               "label"=>$label,
               "content_id"=>$autocomplate->content_id,
               "title"=>$autocomplate->title,
               "small_url"=>$autocomplate->small_url,
               "duration"=>$autocomplate->duration,
               "release_year"=>$autocomplate->release_year,
               "categories"=>$autocomplate->categories,
               "category"=>$category
            );
        }
        echo json_encode($response);
        exit;
     }
  
}
