<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input; //wamt to include 3 files fr send
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Auth;
use App\Category;
use App\Post;

class PostController extends Controller
{
    public function post(){
    	$categories = Category::all();
    	return view('posts.post',['categories'=>$categories]);
    }

    public function addPost(Request $request){
    	$this->validate($request,[
    			'post_title' => 'required',
    			'post_body'  => 'required',
    			'category_id' => 'required',
    			'post_image' => 'required',
    	]);
    	//return "Validation pass";
    	
    	$posts = new Post;
   		$posts->post_title = $request->input('post_title');
   		$posts->user_id = Auth::user()->id;
   		$posts->post_body = $request->input('post_body');
   		$posts->category_id = $request->input('category_id');

     
    if($request->has('post_image')){ //want to use $request because of laravel version 6
      
      $file = $request->file('post_image');  //here also
      $file->move(public_path().'/posts/',$file->getClientOriginalName());
    $url = URL::to("/").'/posts/'.$file->getClientOriginalName();
     }
		$posts->post_image=$url; 
   		 $posts->save();
      	return redirect('home')->with('response','post added successfully');
      

    }
    public function view($post_id){
      $posts = Post::where('id','=',$post_id)->get();//used to get the selected post where post id is eql to var post id
      //return $post;
      $categories = Category::all();
      return view('posts.view',['posts'=>$posts,'categories' => $categories]);
    }
    
    public function edit($post_id){
      $categories = Category::all();
      $posts = Post::find($post_id);
      $category = Category::find($posts->category_id);
      //return $posts;
      //exit();
      return view('posts.edit',['categories'=>$categories, 'posts'=>$posts, 'category'=>$category]);
      //return $post_id;
    }
    public function update(Request $request,$post_id){
        //return $post_id;
      $this->validate($request,[
          'post_title' => 'required',
          'post_body'  => 'required',
          'category_id' => 'required',
          'post_image' => 'required',
      ]);
      //return "Validation pass";
      
      $posts = new Post;
      $posts->post_title = $request->input('post_title');
      $posts->user_id = Auth::user()->id;
      $posts->post_body = $request->input('post_body');
      $posts->category_id = $request->input('category_id');

     
    if($request->has('post_image')){ //want to use $request because of laravel version 6
      
      $file = $request->file('post_image');  //here also
      $file->move(public_path().'/posts/',$file->getClientOriginalName());
    $url = URL::to("/").'/posts/'.$file->getClientOriginalName();
     }
    $posts->post_image=$url; 
    $data =array(
      'post_title' => $posts->post_title,
      'user_id' => $posts->user_id,
      'post_body' => $posts->post_body,
      'category_id' => $posts->category_id,
      'post_image' => $posts->post_image,
    );
      Post::where('id',$post_id)
      ->update($data);
       $posts->update();
        return redirect('home')->with('response','post updated successfully');
      
    }
    
}
