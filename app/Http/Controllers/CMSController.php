<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AvErosNowsPrefer;
use App\Models\AvErosNows;
use App\Models\SubCategory;
use App\Models\GameContent;
use App\Models\VideoContent;
use App\Models\PromotionGame;
use App\Models\PromotionKid;


class CMSController extends Controller
{
    public function index(Request $request)
    {
        $category = "Action";
        $contenttype = "movies";
        $category = ($request->category=="")?$category:$request->category;
        $contenttype = ($request->contenttype=="")?$contenttype:$request->contenttype;
        $data = AvErosNowsPrefer::with('erosnow_data')
        ->where('category', '=' , $category)
        ->where('content_type', '=' , $contenttype)
        ->orderBy('prefer','DESC')->get();
        return view('cms-editor-eros.index',compact('data','category','contenttype'));
    }

    public function store(Request $request)
    {
        $max = 100;
        $prefer = $max;
        $removedMovies = ($request['removed-movie-cards'])?explode("|", $request['removed-movie-cards']):[];
        $category = $request['card-category'];
        $contenttype = $request['card-content'];
        $movieCards = ($request['latest-movie-cards'])?explode("|", $request['latest-movie-cards']):[];
        $latestMovieCards = [];
        foreach($movieCards as $key => $movieCard){
            $prefer = $max - $key;
            $latestMovieCards[] = array(
                'prefer_content_id'=>$movieCard,
                'prefer'=>$prefer,
                'category'=>$category,
                'content_type'=>$contenttype
            );
        }
        if(count($removedMovies) > 0){
            AvErosNowsPrefer::where('category', '=' , $category)
            ->where('content_type', '=' , $contenttype)
            ->delete();
        }
        if(count($latestMovieCards) > 0){
            AvErosNowsPrefer::upsert($latestMovieCards, ['prefer_content_id', 'category','content_type'], ['prefer']);
        }
        return redirect()->route('cms-editor-erosnow.index',['category'=>$category,'contenttype'=>$contenttype])->with('success','Content Applied successfully.');
    }

    public function searchErosnow(Request $request)
    {
        $search = $request->search;
        $category = "Action";
        $contenttype = "movies";
        $category = ($request->category=="")?$category:$request->category;
        $contenttype = ($request->contenttype=="")?$contenttype:$request->contenttype;
        $autocomplateQuery = AvErosNows::orderby('title','asc')
        ->select('content_id','title','small_url','duration','release_year','categories','serial_title');
        if($contenttype == "music"){
        $autocomplateQuery->where('title', 'like', '%' .$search . '%');
        $autocomplateQuery->where('categories', '=', $category);
        $autocomplateQuery->where('content_type','like','MUSIC%');
        }
        if($contenttype == "series"){ 
        $autocomplateQuery->where(function ($query) use ($search) {
            $query->where('title', 'like', '%' .$search . '%')
                  ->orWhere('serial_title', 'like', '%' .$search . '%');
        });
        $autocomplateQuery->where('categories', '=', $category);
        $autocomplateQuery->Where('content_type','like','ORIGINAL%');
        }
        if($contenttype == "movies"){ 
        $autocomplateQuery->where('title', 'like', '%' .$search . '%');
        $autocomplateQuery->where('categories', '=', $category);
        $autocomplateQuery->Where('content_type','like','ORIGINAL%');
        }
        $autocomplateQuery->orderBy('release_year');
        $autocomplateQuery->orderBy('title');
        $autocomplateQuery->orderBy('serial_title');
        $autocomplate = $autocomplateQuery->limit(10)->get();
        $response = array();
        foreach($autocomplate as $autocomplate){
            $title = "";$title = $autocomplate->title;
            if($contenttype == "series"){ 
                $title = $autocomplate->serial_title." (".$autocomplate->title.")";
            }
            $label = "";$label = $title." (".$autocomplate->duration." sec) ".$autocomplate->release_year." ".$autocomplate->categories." ";
            
            $response[] = array(
                "value"=>$autocomplate->content_id,
                "label"=>$label,
                "content_id"=>$autocomplate->content_id,
                "title"=>$title,
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

     public function gamePromotion(Request $request)
     {
        $category = 27; // Arcade
        $contenttype = "games";
        $category = ($request->category=="")?$category:$request->category;
        $contenttype = ($request->contenttype=="")?$contenttype:$request->contenttype;

        $subCategories = SubCategory::where('category_id','=',3)->select('id','name')->get();
        $subCategotyData = SubCategory::where('category_id','=',3)
        ->where('id','=',$category)
        ->select('id','name')->first();

        $data = PromotionGame::with('game_data')
        ->where('category', '=' , $category)
        ->where('content_type', '=' , $contenttype)
        ->orderBy('prefer','DESC')->get();
        
        return view('game-promotions.index',compact('subCategories','data','category','contenttype','subCategotyData'));
     }
  
     
    public function searchGames(Request $request)
    {
        $search = $request->search;
        $category = 27; // Arcade
        $contenttype = "games";
        $category = ($request->category=="")?$category:$request->category;
        $contenttype = ($request->contenttype=="")?$contenttype:$request->contenttype;
        $autocomplateQuery = GameContent::orderby('game_name','asc')
        ->select('id','game_name','img')
        ->where('game_name', 'like', '%' .$search . '%')
        ->where('sub_cat_id', '=', $category);
        $autocomplate = $autocomplateQuery->limit(10)->get();
        $response = array();
        foreach($autocomplate as $autocomplate){
            $label = "";$label = $autocomplate->game_name;
           $response[] = array(
               "value"=>$autocomplate->id,
               "label"=>$label,
               "content_id"=>$autocomplate->id,
               "title"=>$autocomplate->game_name,
               "small_url"=>$autocomplate->img
            );
        }
        echo json_encode($response);
        exit;
     }

     public function gamePromotionStore(Request $request)
     {
         $max = 100;
         $prefer = $max;
         $removedMovies = ($request['removed-movie-cards'])?explode("|", $request['removed-movie-cards']):[];
         $category = $request['card-category'];
         $contenttype = $request['card-content'];
         $movieCards = ($request['latest-movie-cards'])?explode("|", $request['latest-movie-cards']):[];
         $latestMovieCards = [];
         foreach($movieCards as $key => $movieCard){
             $prefer = $max - $key;
             $latestMovieCards[] = array(
                 'prefer_content_id'=>$movieCard,
                 'prefer'=>$prefer,
                 'category'=>$category,
                 'content_type'=>$contenttype
             );
         }
         if(count($removedMovies) > 0){
            PromotionGame::where('category', '=' , $category)
             ->where('content_type', '=' , $contenttype)
             ->delete();
         }
         if(count($latestMovieCards) > 0){
            PromotionGame::upsert($latestMovieCards, ['prefer_content_id', 'category','content_type'], ['prefer']);
         }
         return redirect()->route('game-promotion',['category'=>$category,'contenttype'=>$contenttype])->with('success','Content Applied successfully.');
     }
 
     public function KidPromotion(Request $request)
     {
        $mainCatId = 4;
        $mainSubCatId = $request->mainSubCatId;
        $videoCatId = $request->videoCatId;
        $contenttype = "kids";
        $mainCat = "Kids";
        
        $mainSubCats = SubCategory::where('category_id','=',$mainCatId)->select('id','name')->orderby('name')->get();
        $mainSubCatId = ($mainSubCatId=="")?(!empty($mainSubCats[0]) ? $mainSubCats[0]->id:""):$mainSubCatId;
        $mainSubCat = SubCategory::where('id','=',$mainSubCatId)->select('id','name')->first();
        $mainSubCatId = ($request->mainSubCatId=="")?$mainSubCat->id:$request->mainSubCatId;
        $videoCats = SubCategory::where('category_id',$mainSubCatId)->select('id','name')->orderby('name')->get();
        $newVideoCatId = "";
        foreach($videoCats as $data){
            if($videoCatId == $data->id){
                $newVideoCatId = $data->id;
            }
        }
        $videoCatId = ($newVideoCatId=="")?(!empty($videoCats[0]) ? $videoCats[0]->id:""):$newVideoCatId;
        $videoCat = SubCategory::where('id','=',$videoCatId)->select('id','name')->first();
        $data = PromotionKid::with('kid_data')
        ->where('category', '=' , $videoCatId)
        ->where('main_cat', '=' , $mainCatId)
        ->where('main_sub_cat', '=' , $mainSubCatId)
        ->where('content_type', '=' , $contenttype)
        ->orderBy('prefer','DESC')->get();
        return view('kid-promotions.index',compact('data','mainCat','mainSubCat','mainSubCats','videoCat','videoCats','videoCatId','mainCatId','mainSubCatId','mainCatId','contenttype'));
     }
  
     
    public function searchKids(Request $request)
    {
        $search = $request->search;
        $mainCatId = 4;
        $mainSubCatId = 4;
        $videoCatId =  156;
        $contenttype = "kids";
        $videoCatId = ($request->videoCatId=="")?$videoCatId:$request->videoCatId;
        $mainSubCatId = ($request->mainSubCatId=="")?$mainSubCatId:$request->mainSubCatId;
        $contenttype = ($request->contenttype=="")?$contenttype:$request->contenttype;
        $autocomplateQuery = VideoContent::orderby('content_name','asc')
        ->select('id','content_name','profileImage')
        ->where('content_name', 'like', '%' .$search . '%')
        ->where('cat_id', '=', $mainSubCatId)
        ->where('sub_id', '=', $videoCatId)
        ->orderby('content_name');
        $autocomplate = $autocomplateQuery->limit(10)->get();
        $response = array();
        foreach($autocomplate as $autocomplate){
            $label = "";$label = $autocomplate->content_name;
           $response[] = array(
               "value"=>$autocomplate->id,
               "label"=>$label,
               "content_id"=>$autocomplate->id,
               "title"=>$autocomplate->content_name,
               "small_url"=>$autocomplate->profileImage,
            );
        }
        echo json_encode($response);
        exit;
     }

     public function kidPromotionStore(Request $request)
     {
         $max = 100;
         $prefer = $max;
         $removedMovies = ($request['removed-movie-cards'])?explode("|", $request['removed-movie-cards']):[];
         $videoCatId = $request['card-video-cat-id'];
         $mainSubCatId = $request['card-main-sub-id'];
         $mainCatId = $request['card-main-id'];
         $contenttype = $request['card-content'];
         $movieCards = ($request['latest-movie-cards'])?explode("|", $request['latest-movie-cards']):[];
         $latestMovieCards = [];
         foreach($movieCards as $key => $movieCard){
             $prefer = $max - $key;
             $latestMovieCards[] = array(
                 'prefer_content_id'=>$movieCard,
                 'prefer'=>$prefer,
                 'category'=>$videoCatId,
                 'main_cat'=>$mainCatId,
                 'main_sub_cat'=>$mainSubCatId,
                 'content_type'=>$contenttype
             );
         }
         if(count($removedMovies) > 0){
            PromotionKid::where('category', '=' , $videoCatId)
            ->where('main_cat', '=' , $mainCatId)
            ->where('main_sub_cat', '=' , $mainSubCatId)
            ->where('content_type', '=' , $contenttype)
            ->delete();
         }
         if(count($latestMovieCards) > 0){
            PromotionKid::upsert($latestMovieCards, ['prefer_content_id', 'category','main_cat','main_sub_cat','content_type'], ['prefer']);
         }
         return redirect()->route('kid-promotion',['videoCatId'=>$videoCatId,'mainCatId'=>$mainCatId,'mainSubCatId'=>$mainSubCatId,'contenttype'=>$contenttype])->with('success','Content Applied successfully.');
     }
 


}
