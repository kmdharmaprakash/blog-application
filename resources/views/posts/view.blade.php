@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


                    @if(session('response'))
                        <div class="alert alert-success">{{session('response')}}</div>
                    @endif


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

                        <ul class="">
                            <li role="presentation">
                                <a href="{{url("/like/{$post->id}")}}">
                                    <span class="fa fa-thumbs-up">Like ({{$likeCtr}})</span>
                                </a>
                            </li>
                            </ul> 
                            <ul>          
                            <li role="presentation">
                                <a href="{{url("/dislike/{$post->id}")}}">
                                    <span class="fa fa-eye">Dislike ({{$dislikeCtr}})</span>
                                </a>
                            </li>
                        </ul>
                        <ul>
                            <li role="presentation">
                                <a href="{{url("/comment/{$post->id}")}}">
                                    <span class="fa fa-trash">Comment ()</span>
                                </a>
                            </li>
                        </ul><br>

                         <form method="POST" action='{{url ("/comment/{$post->id}")}}'>
                    @csrf
                    <div class="form-group">
                        <textarea id="comment" rows="6" class="form-control" name="comment" required autofocus=""></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block">Post Comment</button>
                    </div>

                    </form>

                    <h3>Comments</h3>

                    @if(count($comments) > 0)
                    @foreach($comments as $comment)
                        <p>{{$comment->comment}}</p>
                        <p>posted by: {{$comment->name}}</p>
                        <hr/>
                    @endforeach
                    @endif
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



