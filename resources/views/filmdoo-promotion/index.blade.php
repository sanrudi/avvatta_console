@extends('layout.template')
@push('css-links')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/hot-sneaks/jquery-ui.css" >
@endpush
@section('content')
<div class="container">
  <h6>{{ $subCategotyData->name  }}({{ $contenttype  }})</h6>
  <hr>
  <div class="row mr-5">
    <div class="form-group col-xl-6 col-md-12"> 
      <label for="movie_search">Search & Add</label>
      <input type="text" class="form-control" id='movie_search' placeholder="Search & Add {{ $contenttype  }} here...">
    </div>
    <div class="form-group col-xl-6 col-md-12"> 
    <form method="get" action="{{ route('filmdoo-promotion') }}">
  <div class="row">
    <div class="form-group col-xl-6 col-md-6">
      <label for="category">Category</label>
        <select name="category" id="category" class="form-control" onchange="this.form.submit()">
        @foreach($subCategories as $subCategory)
        <option value="{{$subCategory->id}}" @if($category == "Action") selected="selected" @endif>{{$subCategory->name}}</option>
        @endforeach
        </select>
    </div>
    <div class="form-group col-xl-6 col-md-6">
      <label for="content">Content Type</label>
        <select name="contenttype" id="contenttype" class="form-control" onchange="this.form.submit()">
          <option value="filmdoo" @if($contenttype == "filmdoo") selected="selected" @endif>Filmdoo</option>
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
  
  <form method="post" action="{{ route('filmdoo-promotion') }}">
    @csrf
    <div class="row mr-5" id="sortable">
      @foreach ($data as $key => $content)
      <div class="col-xl-2 col-md-3 col-sm-6 movie-card py-2 ui-state-default">
        <div class="card text-center h-100">
          <img class="card-img-top handle bg-dark" src="{{ $content->filmdoo_data->img  }}" alt="">
          <div class="card-body card-frame handle bg-dark p-1">
            <h6 class="text-white "><small>{{ $content->filmdoo_data->filmdoo_name  }}</small></h6>
            <input type="hidden" name="content_id[]" class="movie-content-id" value="{{ $content->filmdoo_data->id  }}">
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
            url:"{{route('filmdoo-promotion-search')}}",
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
            movieCard +='<img class="card-img-top handle bg-dark" src="'+ui.item.small_url+'" alt="">';
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