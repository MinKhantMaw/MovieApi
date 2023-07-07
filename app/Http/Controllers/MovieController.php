<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PDF;

class MovieController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['indexPage', 'details','getRelatedMovies','download']);
    }

    // not authenticated
    public function indexPage()
    {
        $movies = Movie::with(['user', 'comments'])->paginate(7)->withQueryString();
        return ApiResponse::success('Movie List', $movies, 200);
    }

    public function index()
    {
        $movies = Movie::with(['user', 'comments'])->paginate(7)->withQueryString();
        return ApiResponse::success('Movie List', $movies, 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'summary' => 'required',
            'image' => 'required',
            'genres' => 'required',
            'author' => 'required',
            'tags' => 'required',
            'imdb_rating' => 'required',
            'pdf' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::fail('Validation Error', $validator->errors()->all(), 422);
        }

        $img_name = "";
        if ($request->hasFile('image')) {
            $image_file = $request->file('image');
            $img_name = time() . '-' . uniqid() . '-' . $image_file->getClientOriginalName();

            Storage::disk('public')->put(
                'images/' . $img_name,
                file_get_contents($image_file)
            );
        }

        $movie = Movie::create([
            'title' => $request->title,
            'summary' => $request->summary,
            'image' => $img_name,
            'url' => asset('/storage/images/' . $img_name),
            'genres' => $request->genres,
            'author' => $request->author,
            'tags' => $request->tags,
            'imdb_rating' => $request->imdb_rating,
            'pdf' => $request->pdf,
            'user_id' => Auth::user()->id,
        ]);

        return ApiResponse::success('Movie Created Success', $movie, 201);
    }

    public function details($id)
    {
        $movie = Movie::with('comments')->find($id);
        return ApiResponse::success('Movie Details', $movie, 200);
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'summary' => 'required',
            'genres' => 'required',
            'author' => 'required',
            'tags' => 'required',
            'imdb_rating' => 'required',
            'pdf' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::fail('Validation Error', $validator->errors()->all(), 422);
        } else {

            $movie->title = $request->title;
            $movie->summary = $request->summary;
            $movie->genres = $request->genres;
            $movie->author = $request->author;
            $movie->tags = $request->tags;
            $movie->imdb_rating = $request->imdb_rating;
            $movie->pdf = $request->pdf;
            $movie->save();

            if ($request->hasFile('image')) {
                $img_name = "";
                $image_file = $request->file('image');
                $img_name = time() . '-' . uniqid() . '-' . $image_file->getClientOriginalName();

                Storage::disk('public')->put(
                    'images/' . $img_name,
                    file_get_contents($image_file)
                );
                Storage::disk('public')->delete("images/$movie->image");

                $movie->image = $img_name;
                $movie->save();
            }

            return ApiResponse::success('Movie Updated Successfully', $movie, 200);
        }
    }

    public function delete($id)
    {
        $movie = Movie::find($id);
        Storage::disk('public')->delete("images/$movie->image");
        $movie->delete();
        return ApiResponse::success('Movie Deleted Successfully', null, 200);
    }

    public function getRelatedMovies(Request $request, $id)
    {
        $currentMovie = Movie::findOrFail($id);

        $relatedMovies = Movie::with('comments')->where('author', $currentMovie->author)
            ->orWhere('genres', $currentMovie->genres)
            ->orWhere('tags', $currentMovie->tags)
            ->orWhere('id', '!=', $currentMovie->id)
            ->orderByDesc('imdb_rating', '>=', 4.5)
            ->limit(7)
            ->get();

        return ApiResponse::success('Related movies have been added',  $relatedMovies, 200);
    }

    public function download(Request $request, $id){
        $movie = Movie::findOrFail($id);
        $pdf = PDF::loadView('details', compact('movie'));

        $filename = 'movie.pdf';
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        return $pdf->download($filename, $headers);

    }
}
