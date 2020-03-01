<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input; //wamt to include 3 files fr send
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Category;
use App\Post;
use App\Like;
use App\Dislike;
use App\Comment;
use App\Profile;

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
      $likePost = Post::find($post_id);

      $likeCtr = Like::where(['post_id'=> $post_id])
                ->count(); //for likes 

      $dislikeCtr = Dislike::where(['post_id'=>$post_id])
                    ->count(); //dislikes

      $categories = Category::all();

      $comments = DB::table('users')->join('comments',
                    'users.id','=','comments.user_id')
                  ->join('posts','comments.post_id','=','posts.id')
                  ->where(['post_id' => $post_id])
                  ->get(); //for comments
                  //return $comments;
                  //exit();

      return view('posts.view',['posts'=>$posts,'categories' => $categories,
                                'likeCtr' => $likeCtr, 'dislikeCtr' => $dislikeCtr, 'comments' => $comments 
    ]);
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
    public function deletePost($post_id){
      Post::where('id',$post_id)
      ->delete();
       return redirect('home')->with('response','post deleted successfully');
    }

    public function category($cat_id){

      $categories = Category::all();
      $posts = DB::table('posts')
                ->join('categories','posts.category_id','=','categories.id')
                ->select('posts.*','categories.*')
                ->where(['categories.id' => $cat_id])
                ->get();
                //return $posts;
                //exit();
      return view('categories.categoriesposts',['categories' => $categories, 'posts' => $posts]);
    }
    public function like($id){
      $loggedin_user = Auth::user()->id;
      $like_user = Like::where(['user_id'=>$loggedin_user, 'post_id' => $id])->first(); //user who like the post cannot like the post again,if it confuse see db table and check it
        if(empty($like_user->user_id)){
            $user_id = Auth::user()->id;
            $email = Auth::user()->email;
            $post_id = $id;

            $like = new Like;
            $like->user_id = $user_id;
            $like->email   = $email;
            $like->post_id = $post_id;
            $like->save();

            return redirect("/view/{$id}"); 
        }else{
            return redirect("/view/{$id}"); 
        }
    }

    public function dislike($id){

      $loggedin_user = Auth::user()->id;
      $dislike_user =  Dislike::where(['user_id'=>$loggedin_user, 'post_id'=>$id])->first(); 

        if(empty($dislike_user->user_id)){
            $user_id = Auth::user()->id;
            $email = Auth::user()->email;
            $post_id = $id;

            $dislike = new Dislike;
            $dislike->user_id = $user_id;
            $dislike->post_id = $id;
            $dislike->email = $email;
            $dislike->save();

            return redirect("/view/{$id}");
        }else{
            return redirect("/view/{$id}");
        }

    }

    public function comment(Request $request,$post_id){
      //return $post_id;

      $this->validate($request, [
            'comment' => 'required',
      ]);

      $comment = new Comment;
      $comment->user_id=Auth::user()->id;
      $comment->post_id=$post_id;
      $comment->comment = $request->input('comment');
      $comment->save();
      return redirect("/view/{$post_id}")->with('response','comment added succesfully');
    }
    public function search(Request $request){
      //return $request;
      $user_id = Auth::user()->id;
      $profile = Profile::find($user_id);
      $keyword = $request->input('search');
  //$posts = Post::where('post_title','LIKE','%'.$keyword.'%')->get();//this is the method which we can view the post according to that we search a post name in search bar, but i changed to category type which below.
      $posts   = DB::table('posts')->join('categories','posts.category_id','='         ,'categories.id')
                 ->select('posts.*','categories.*')
                 ->where('category','LIKE','%'.$keyword.'%')
                 ->get();//here i changed which we can view the post according to search by category method..
                 //if u want search by post method look above..

                
      //return $posts;
      //exit();
      return view('posts.searchposts', ['profile'=>$profile ,'posts'=>$posts]);
    }

}
