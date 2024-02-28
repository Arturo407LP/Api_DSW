<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listadoPosts = Post::all();

        $posts = [];

        foreach ($listadoPosts as $post) {
            $data = DB::table('posts')
                ->join('posts_types', 'posts.posts_types_id', '=', 'posts_types.id')
                ->select(
                    'posts.id', 'posts.titulo', 'posts.descripcion', 'posts.imagen', DB::raw('posts_types.nombre as tipo_publicacion'),
                    'fecha_publicacion', 'posts.fecha_inicio', 'posts.fecha_fin', 'posts.activo'
                )
                ->where('posts_types.id', '=', $post->posts_types_id)
                ->get();

            array_push($posts, $data);
        }



        return response()->json([
            'status' => true,
            'data' => $posts
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
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        

        foreach ($request->shop_id as $shop_id) {
            $post->shops()->attach($shop_id);
        }


        return response()->json([
            'status' => true,
            'message' => 'Nueva actividad creada',
            'id_actividad' => $post->id
        ], 200);
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
    public function update(Request $request, string $id)
    {
        try {
            $post = Post::find($id);
            $post->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Actividad modificada',
                'id_actividad' => $post->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Error',
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

            return response()->json([
                'status' => true,
                'message' => 'Actividad eliminada',
                'id_actividad' => $post->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => 'Actividad no encontrada',
            ], 404);
        }
    }
}
