@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}</div>
                        @endforeach
                    @endif

                    @if(session('response'))
                        <div class="alert alert-success">{{session('response')}}</div>
                    @endif

                <div class="card-header">Dashboard</div>
                <div class="panel-body">
                    <div class="col-md-4">
                        {{----}}
                        @if(!empty($profile))
                        <img src="{{ $profile->profile_pic }}" class="avatar" alt="">
                        @else
                        <img src="{{url('images/avatar.png')}}" class="avatar" alt="">
                        @endif

                         @if(!empty($profile))
                        <p class="lead">{{$profile->name}}</p>
                        @else
                        <p></p>
                        @endif

                        @if(!empty($profile))
                        <p class="lead">{{$profile->designation}}</p>
                        @else
                        <p></p>
                        @endif

                    </div>
                    <div class="col-md-8">
                        <hr>
                         @if(count($posts)>0)
                        @foreach($posts->all() as $post)
                        <h2 >{{$post->post_title}}</h2>
                        <img class="blog-image" src="{{$post->post_image}}">
                        <p class="blog-font">{{$post->post_body, 0 , 150}}</p>

                        <ul class="">
                            <li role="presentation">
                                <a href="{{url("/view/{$post->id}")}}">
                                    <span class="fa fa-eye">VIEW</span>
                                </a>
                            </li>
                            </ul> 
                            <ul>          
                            <li role="presentation">
                                <a href="{{url("/edit/{$post->id}")}}">
                                    <span class="fa fa-eye">EDIT</span>
                                </a>
                            </li>
                        </ul>
                        <ul>
                            <li role="presentation">
                                <a href="{{url("/delete/{$post->id}")}}">
                                    <span class="fa fa-trash">DELETE</span>
                                </a>
                            </li>
                        </ul><br>

                        <cite style="">Posted on: {{date('M j, Y H:i', strtotime($post->updated_at))}}</cite>
                        <hr>
                        @endforeach
                    @else
                    <p>no post available</p>
                    @endif

                    </div>
                </div>
                
                
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                 
                    @if(count($errors)>0)
                        @foreach($errors->all() as $error)
                        <div class="alert alert-danger">{{$error}}
                    You are logged in!
                    @endforeach
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>
@endsection



