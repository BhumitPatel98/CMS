<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\CreatePostsRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use App\Category;
use App\Tag;
use GuzzleHttp\Middleware;

use function PHPSTORM_META\map;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */

    public function __construct()
    {
        $this->Middleware('verifyCategoriesCount')->only('create','store');
    }
    public function index()
    {
        return view('posts.index')->with('posts',Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostsRequest $request)
    {

        //dd($request->all());
        //upload a image to storage
        $image = $request->image->store('posts');

        //create a post

        $post = Post::create([

            'title' => $request->title,
            'description' => $request->description,
            'content'=> $request->content,
            'image' => $image,
            'published_at' => $request->published_at,
            'category_id' => $request->category
        ]);

        if($request->tags)
        {
            $post->tags()->attach($request->tags);
        }

        //flash message
        session()->flash('success','Post Created Successfully');

        //redirect

        return redirect( route('posts.index') );

    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
     
        return view('posts.create')->with('post',$post)->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request,Post $post)
    {

        $data = $request->only(['title','discription','published_at','content']);

        //check if new image
        if($request->hasFile('image'))
        {
            //upload it
            $image = $request->image->store('posts');

            //delete old one
            $post->deleteImage();

            $data['image'] = $image;
        
        }

        if($request->tags)
        {
            $post->tags()->sync($request->tags);
        }

        //update attributes
        $post -> update($data);

        //flash message

        session()->flash('success','Update Post successfully');

        //redirect user
        return redirect(route('posts.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->where('id',$id)->first();


        if($post->trashed())
        {
            $post->deleteImage();

            $post->forceDelete();
        }
        else
        {
            $post->delete();
        }
       

        session()->flash('error','Post Delete Successfully');

        return redirect(route('posts.index'));
    }

    /**
     * Displayed all trashed posts
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {

        $trashed = Post::onlyTrashed()->get();

        return view('posts.index')->with('posts',$trashed);


    }

    public function restore($id){

        $post = Post::withTrashed()->where('id',$id)->first();

        $post->restore();

        session()->flash('success','Post Restore Successfully');

        return redirect()->back();
    }
    
}
