@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-end mb-2">
    <a href="/posts/create " class=" btn btn-success " style="color: black">Add Post</a>
</div>

<div class="card card-default">

        <div class="card-header">
            Posts
        </div>

        <div class="card-body">

            @if ($posts->count() > 0)
                <table class="table">

                    <thead>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>     </th>
                        <th>     </th>
                    </thead>
        
                    <tbody>
        
                        @foreach ($posts as $post)
                    
                            <tr>
                                <td>
                                    <img src="{{asset(URL::to('/storage/' .$post->image))}}" height="50px" width="70px" alt="">
                                </td>
        
                                <td>
                                    {{ $post->title }}
                                </td>

                                <td>
                                    <a href="{{ route('categories.edit', $post->category_id) }}">
                                        {{ $post->category->name }}                                        
                                    </a>
                                    
                                </td>
        
                                @if ($post->trashed())
                                    <td>
                                        <form action="{{ route('restore-posts',$post->id) }}" method="post">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-info ">Restore</button>
                                        </form>    
                                    </td>
                                    
                                @else

                                    <td>
                                        <a href="{{ route('posts.edit',$post->id) }}" class="btn btn-info ">Edit</a>
                                    </td>

                                @endif
                            
                                <td>
                                    <form action="{{ route('posts.destroy',$post->id )}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger ">
                                            {{ $post->trashed() ? 'Delete' : 'Trash' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
        
                        @endforeach
                        
                    </tbody>
        
                </table>

            @else
                
                <h3 class="text-center"> No Post Yet </h3>

            @endif

        </div>
</div>
    
@endsection