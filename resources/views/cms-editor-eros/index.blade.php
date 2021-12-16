@extends('layout.template')
@push('css-links')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/hot-sneaks/jquery-ui.css" >
@endpush
@section('content')
<div class="container">
  <h6>Erosnow - {{ $category  }}</h6>
  <hr>
  <div class="row mr-5">
    <div class="form-group col-xl-6 col-md-12"> 
      <label for="movie_search">Search & Add</label>
      <input type="text" class="form-control" id='movie_search' placeholder="Search & Add Erosnow {{ str_replace('-', ' ', $contenttype)  }} here...">
    </div>
    <div class="form-group col-xl-6 col-md-12"> 
    <form method="get" action="{{ route('cms-editor-erosnow.store') }}">
  <div class="row">
    <div class="form-group col-xl-6 col-md-6">
      <label for="category">Category</label>
        <select name="category" id="category" class="form-control" onchange="this.form.submit()">
        <option value="Action" @if($category == "Action") selected="selected" @endif>Action</option>
        <option value="Animated Series" @if($category == "Animated Series") selected="selected" @endif>Animated Series</option>
        <option value="Animation" @if($category == "Animation") selected="selected" @endif>Animation</option>
        <option value="B-Town" @if($category == "B-Town") selected="selected" @endif>B-Town</option>
        <option value="Bengali" @if($category == "Bengali") selected="selected" @endif>Bengali</option>
        <option value="Classical" @if($category == "Classical") selected="selected" @endif>Classical</option>
        <option value="Classics" @if($category == "Classics") selected="selected" @endif>Classics</option>
        <option value="Comedy" @if($category == "Comedy") selected="selected" @endif>Comedy</option>
        <option value="Crime" @if($category == "Crime") selected="selected" @endif>Crime</option>
        <option value="Dance" @if($category == "Dance") selected="selected" @endif>Dance</option>
        <option value="Devotional" @if($category == "Devotional") selected="selected" @endif>Devotional</option>
        <option value="Do-It-Yourself" @if($category == "Do-It-Yourself") selected="selected" @endif>Do-It-Yourself</option>
        <option value="Drama" @if($category == "Drama") selected="selected" @endif>Drama</option>
        <option value="Emotional" @if($category == "Emotional") selected="selected" @endif>Emotional</option>
        <option value="Family" @if($category == "Family") selected="selected" @endif>Family</option>
        <option value="Festival" @if($category == "Festival") selected="selected" @endif>Festival</option>
        <option value="Film" @if($category == "Film") selected="selected" @endif>Film</option>
        <option value="Folk" @if($category == "Folk") selected="selected" @endif>Folk</option>
        <option value="Food" @if($category == "Food") selected="Food" @endif>Thriller</option>
        <option value="Happy" @if($category == "Happy") selected="selected" @endif>Happy</option>
        <option value="Health & Fitness" @if($category == "Health & Fitness") selected="selected" @endif>Health & Fitness</option>
        <option value="Horror" @if($category == "Horror") selected="selected" @endif>Horror</option>
        <option value="Humour" @if($category == "Humour") selected="selected" @endif>Humour</option>
        <option value="Indian Regional" @if($category == "Indian Regional") selected="Indian Regional" @endif>Indian Regional</option>
        <option value="Inspirational" @if($category == "Inspirational") selected="selected" @endif>Inspirational</option>
        <option value="Kids" @if($category == "Kids") selected="selected" @endif>Kids</option>
        <option value="Malayalam" @if($category == "Malayalam") selected="selected" @endif>Malayalam</option>
        <option value="Mature" @if($category == "Mature") selected="selected" @endif>Mature</option>
        <option value="music" @if($category == "music") selected="selected" @endif>Music</option>
        <option value="Musical" @if($category == "Musical") selected="selected" @endif>Musical</option>
        <option value="Mystery" @if($category == "Mystery") selected="selected" @endif>Mystery</option>
        <option value="Non-Film" @if($category == "Non-Film") selected="selected" @endif>Non-Film</option>
        <option value="Patriotic" @if($category == "Patriotic") selected="selected" @endif>Patriotic</option>
        <option value="Patriotic" @if($category == "Patriotic") selected="selected" @endif>Patriotic</option>
        <option value="Pop" @if($category == "Pop") selected="selected" @endif>Pop</option>
        <option value="Punjabi" @if($category == "Punjabi") selected="selected" @endif>Punjabi</option>
        <option value="Remix" @if($category == "Remix") selected="selected" @endif>Remix</option>
        <option value="Romance" @if($category == "Romance") selected="selected" @endif>Romance</option>
        <option value="Romantic Comedy" @if($category == "Romantic Comedy") selected="selected" @endif>Romantic Comedy</option>
        <option value="Romantic" @if($category == "Romantic") selected="selected" @endif>Romantic</option>
        <option value="Sports" @if($category == "Sports") selected="selected" @endif>Sports</option>
        <option value="Thrillers" @if($category == "Thrillers") selected="selected" @endif>Thrillers</option>
        </select>
    </div>
    <div class="form-group col-xl-6 col-md-6">
      <label for="content">Content Type</label>
        <select name="contenttype" id="contenttype" class="form-control" onchange="this.form.submit()">
          <option value="movies" @if($contenttype == "movies") selected="selected" @endif>Movies</option>
          <option value="series" @if($contenttype == "series") selected="selected" @endif>Series</option>
          <option value="music" @if($contenttype == "music") selected="selected" @endif>Music</option>
        </select>
    </div>
    </div>
      </form>
    </div>
@if(session()->has('success'))
    <div class="col-md-12">
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    </div>
@endif
    <div class="col-md-12">
    <div class="alert alert-danger" style="display: none;">
        Content already Exist.
    </div>
    </div>
  </div>
  
  <form method="post" action="{{ route('cms-editor-erosnow.store') }}">
    @csrf
    <div class="row mr-5" id="sortable">
      @foreach ($data as $key => $content)
      <div class="col-xl-2 col-md-3 col-sm-6 movie-card py-2 ui-state-default">
        <div class="card text-center h-100">
          <img class="card-img-top handle bg-dark" src="https://www.avvatta.com:8100/avvata/public/uploads/{{ $content->erosnow_data->small_url  }}" alt="">
          <div class="card-body card-frame handle bg-dark p-1">
            <h6 class="text-white "><small>{{ $content->erosnow_data->title  }}</small></h6>
            <input type="hidden" name="content_id[]" class="movie-content-id" value="{{ $content->erosnow_data->content_id  }}">
          </div>
          <div class="card-footer p-1">
            <sapn class="card-link remove"><small>Remove</small></span>
            </div>
          </div>
        </div>
        @endforeach
        <div class="col-xl-12 col-sm-12 p-4 text-center">
          <input type="hidden" name="latest-movie-cards" id="latest-movie-cards" value="">
          <input type="hidden" name="removed-movie-cards" id="removed-movie-cards" value="">
          <input type="hidden" name="card-category" id="card-category" value="{{ $category  }}">
          <input type="hidden" name="card-content" id="card-content" value="{{ $contenttype  }}">
          <button type="submit" class="btn btn-primary">Apply</button>
        </div>
      </div> 
    </form>
  </div>
  @endsection
  @push('js-links')
  <!-- Drag -->
  <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
  <!-- Drag -->
  @endpush
  @section('js-content')
  <script>
    $( function() {
      $( "#sortable" ).sortable({
        handle: '.handle',
        animation: 150,
        stop: function(e, ui) {
          reOrderCards();
        }
      });
    } );
    
    $('#removed-movie-cards').val("");
    $(document).on("click", ".remove", function() {
      var removed_id = $(this).closest(".movie-card").find('.movie-content-id').val(); 
      $("#removed-movie-cards").val(function() {
        if(this.value !=""){
          return this.value +"|"+ removed_id;
        }else{
          return removed_id;
        }
      });
      $(this).closest(".movie-card").remove(); 
      reOrderCards();
    });

    function reOrderCards(){
      var latestMovieCards = "";
      $('#latest-movie-cards').val("");
      $('#sortable').find('.card-frame').each(function(i, el){
        var latestContent = $(this).find('input.movie-content-id').val();
        if(latestMovieCards !=""){
          latestMovieCards = latestMovieCards +"|"+ latestContent;
        }else{
          latestMovieCards = latestContent;
        }
      });
      $('#latest-movie-cards').val(latestMovieCards);
    }
  </script>

  <script type="text/javascript">
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var category = $('#card-category').val();
    var contenttype = $('#contenttype').val();
    $(document).ready(function(){
      $( "#movie_search" ).autocomplete({
        source: function( request, response ) {
          $.ajax({
            url:"{{route('erosnow-search')}}",
            type: 'post',
            dataType: "json",
            data: {
              _token: CSRF_TOKEN,
              search: request.term,
              category: category,
              contenttype: contenttype
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
          $('#movie_search').val("");

          var allowCard = 1;
          $('#sortable').find('.card-frame').each(function(i, el){
            var content_id = $(this).find('input.movie-content-id').val();
            if(content_id == ui.item.content_id){
              allowCard = 0;
              $(".alert-danger").show();
              $(".alert-danger").delay(3000).slideUp(300);
            }
          });

          var movieCard = "";
          if(allowCard){
            movieCard +='<div class="col-xl-2 col-sm-6 movie-card py-2 ui-state-default">';
            movieCard +='<div class="card text-center h-100">';
            movieCard +='<img class="card-img-top handle bg-dark" src="https://www.avvatta.com:8100/avvata/public/uploads/'+ui.item.small_url+'" alt="">';
            movieCard +='<div class="card-body card-frame handle bg-dark p-1">';
            movieCard +='<h6 class="text-white "><small>'+ui.item.title+'</small></h6>';
            movieCard +='<input type="hidden" name="content_id[]" class="movie-content-id"  value="'+ui.item.content_id+'">';
            movieCard +='</div>';
            movieCard +='<div class="card-footer p-1">';
            movieCard +='<sapn class="card-link remove"><small>Remove</small></span>';
            movieCard +='</div>';
            movieCard +='</div>';
            movieCard +='</div>';
            $("#sortable").prepend(movieCard);
            reOrderCards();
          }
          return false;
        }
      });


      reOrderCards();
      $("#category").val("{{ $category  }}");
      $(".alert").delay(5000).slideUp(300);
    });

  </script>

  @endsection