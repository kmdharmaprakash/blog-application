@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">Dashboard</div>
                <div class="panel-body">
                    <div class="col-md-4">
                       <ul class="list-group">
                        @if(count($categories)>0)
                                @foreach($categories->all() as $category)
                                    <li class="list-group-item"><a href='{{url("category/{$category->id}")}}'>{{$category->category}}</a>
                                    </li>
                                @endforeach
                            @else
                            <p>No Category Found</p>
                        @endif
                        </ul>
                    </div></div>
                         <div class="col-md-8">
                        <hr>
                         @if(count($posts)>0)
                        @foreach($posts->all() as $post)
                        <h2 >{{$post->post_title}}</h2>
                        <img class="blog-image" src="{{$post->post_image}}">
                        <p class="blog-font">{{substr($post->post_body,0,150)}}</p>

                       

                        <cite style="">Posted on: {{date('M j, Y H:i', strtotime($post->updated_at))}}</cite>
                        <hr>
                        @endforeach
                    @else
                    <p>no post available</p>
                    @endif

                    </div>

                    </div>
                </div>
             
            </div>
        </div>
    </div>
</div>
@endsection



