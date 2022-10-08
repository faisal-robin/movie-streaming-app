<?php

namespace App\Http\Controllers;

use App\Models\ImdbId;
use App\Models\Movie;
use App\Models\RentMovie;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Rooxie\Laravel\Facades\OMDb;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('movie.index');
    }

    public function loadMovie(Request $request)
    {

        $search_value = $request->search_value;

        //set offset limit for pagenation
        $offset = $request->limit ? $request->limit : 0;
        $limit = $request->limit ? 8 + $request->limit : 8;

        $query = Movie::query();

        //get rents movie for basic user
        if(Auth::user()->subscription_type == 'basic'){
            $user_id = Auth::user()->id;
            $query->with(['rents' => function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            }]);
        }

        //if request has search valu search by title or tags
        $query->when($search_value, function ($q) use ($search_value) {
            $q->where(function ($query) use ($search_value) {
                $query->where('title', 'LIKE', "%{$search_value}%")
                    ->orWhere('tag', 'LIKE', "%{$search_value}%");
            });
        });

        $movie_list = $query->offset($offset)->limit($limit)->get();

        return view('movie.load_movie', compact('movie_list'));
    }

    public function showMovie(Request $request)
    {
        //validate request data
        $request->validate([
            'id' => 'required',
        ]);

        if(Auth::user()->subscription_type == 'basic'){
            $movie =  Movie::find($request->id);

            $now = date('Y-m-d H:i:s');

            //check movie in the valid rent period
            if(($now >= $movie->rent_period_from) && ($now <= $movie->rent_period_to)){
                return view('movie.show_movie');
            }else{
                return ['status' => 'error', 'msg' => 'Movie not in valid time period'];
            }
        }else{
            return view('movie.show_movie');
        }
    }

    public function import()
    {
        try {
            //get all licence imdb ids
            $imdb_ids = ImdbId::pluck('imdb_id');
            foreach ($imdb_ids as $id) {
                //call omdb api
                try {
                    $movie_info = OMDb::getByImdbId($id);
                    $movie_info = $movie_info->toArray();

                    $data = array(
                        'imdb_id' => $movie_info['ImdbId'],
                        'title' => $movie_info['Title'],
                        'release_year' => $movie_info['Year'],
                        'poster' => $movie_info['Poster'],
                        'directors' => json_encode($movie_info['Director']),
                        'casts' => json_encode($movie_info['Actors']),
                    );

                    // update or create for reduce duplicate entries
                    Movie::updateOrCreate($data, [$id]);

                } catch (\Rooxie\Exception\IncorrectImdbIdException $e) {
                    // Incorrect IMDb ID "gj349gj349gj34"
                    throw new \Exception($e->getMessage());
                }

            }

            return ['status' => 'success', 'msg' => 'Import Successfully'];

        } catch (\Exception $exception) {
            return ['status' => 'error', 'data' => $exception->getMessage()];
        }
    }

    public function rent(Request $request)
    {
        try {

            //check user is basic
            if (Auth::user()->subscription_type != 'basic') {
                return ['status' => 'error', 'msg' => 'Only basic user can be rent movie'];
            }

            //validate request data
            $request->validate([
                'id' => 'required',
            ]);

            $movie =  Movie::find($request->id);

            $now = date('Y-m-d H:i:s');

            //check movie in the valid rent period
            if(($now >= $movie->rent_period_from) && ($now <= $movie->rent_period_to)){
                $rent_movie = new RentMovie();
                $rent_movie->user_id = Auth::user()->id;
                $rent_movie->movie_id = $request->id;
                $rent_movie->save();
                return ['status' => 'success', 'msg' => 'Rent movie successfully'];
            }else{
                return ['status' => 'error', 'msg' => 'Movie not in valid time period'];
            }


        } catch (\Exception $exception) {

            return ['status' => 'error', 'msg' => $exception->getMessage()];
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $movie_info = Movie::find($request->id);
        return view('movie.edit_movie', compact('movie_info'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //validate request data
        $request->validate([
            'id' => 'required|integer',
            'tag' => 'required|string',
            'plan_type' => 'required|string',
            'rent_period' => 'required',
            'rent_price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        try {
            //explode date
            $date_range = explode(' - ', $request->rent_period);

            $movie = Movie::find($request->id);
            $movie->plan_type = $request->plan_type;
            $movie->tag = $request->tag;
            $movie->rent_price = $request->rent_price;
            $movie->rent_period_from = $date_range[0]; //from date
            $movie->rent_period_to = $date_range[1]; //to date
            $movie->save();

            return ['status' => 'success', 'msg' => 'Update movie successfully'];

        } catch (\Exception $exception) {

            return ['status' => 'error', 'msg' => $exception->getMessage()];
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Movie::find($request->id)->delete();
        return ['status' => 'success', 'msg' => 'Deleted successfully'];
    }
}
