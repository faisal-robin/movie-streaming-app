<?php

namespace Tests\Unit;

use App\Models\Movie;
use App\Models\RentMovie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MovieRentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testImportMovie()
    {
        $this->call('POST','movie.import',[]);
        $this->assertTrue(true);
    }

    public function testMovieRent()
    {
        $movie = Movie::first();

        $user =  User::where('subscription_type','premium')->first();

        $this->actingAs($user);

        $response = $this->call('POST','movie.rent',[
            'id' => $movie->id
        ]);

        $response->assertStatus($response->status(),200);
    }
}
