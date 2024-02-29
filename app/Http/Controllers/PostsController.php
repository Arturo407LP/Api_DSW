<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middlewares\RoleMiddleware;


class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:SeePost')->only('index');
        $this->middleware('can:EditPost')->only('index', 'store', 'update', 'destroy');

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $listadoPosts = DB::table('posts')
        ->join('posts_types', 'posts.posts_types_id', '=', 'posts_types.id')
        ->select(
            'posts.id', 'posts.titulo', 'posts.descripcion', 'posts.imagen', DB::raw('posts_types.nombre as tipo_publicacion'),
            'fecha_publicacion', 'posts.fecha_inicio', 'posts.fecha_fin', 'posts.activo'
        )
        ->get();

        foreach ($listadoPosts as $post) {
            $post->shops = DB::table("shops")
            ->join('post_shop', 'post_shop.shop_users_id', '=', 'shops.users_id')
            ->select('post_shop.shop_users_id', 'shops.nombre')
            ->where('post_shop.post_id', '=', $post->id)
            ->get();

            $post->tags = DB::table("tags")
            ->join('post_tag', 'post_tag.tag_id', '=', 'tags.id')
            ->select('post_tag.tag_id', 'tags.nombre')
            ->where('post_tag.post_id', '=', $post->id)
            ->get();
        }

        return response()->json([
            'status' => true,
            'data' => $listadoPosts
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try{
            $post = Post::create($request->all());
           
            try {
                foreach ($request->shops_id as $shops_id) {
                    $post->shops()->attach($shops_id);
                }
    
                foreach ($request->tags_id as $tags_id) {
                    $post->tags()->attach($tags_id);
                }
            } catch (\Throwable $th) {

                $post = Post::find($post->id);
                $post->delete();
    
                $post_shop = DB::table('post_shop')->where('post_id', '=', $post->id);
                $post_shop->delete();
                $post_tag = DB::table('post_tag')->where('post_id', '=', $post->id);
                $post_tag->delete();

                return response()->json([
                    'status' => false,
                    'error' => throw $th
                ], 404);
            }
            

            return response()->json([
                'status' => true,
                'message' => 'Nueva actividad creada',
                'id_actividad' => $post->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'error' => throw $th
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            $post = Post::find($id);
            $post->update($request->all());

            $post->shops()->sync($request->shops_id);
            $post->tags()->sync($request->tags_id);


            return response()->json([
                'status' => true,
                'message' => 'Actividad modificada',
                'id_actividad' => $post->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => throw $th,
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $post = Post::find($id);
            $post->delete();

            $post_shop = DB::table('post_shop')->where('post_id', '=', $id);
            $post_shop->delete();
            $post_tag = DB::table('post_tag')->where('post_id', '=', $id);
            $post_tag->delete();


            return response()->json([
                'status' => true,
                'message' => 'Actividad eliminada',
                'id_actividad' => $post->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => throw $th,
            ], 404);
        }
    }
}
