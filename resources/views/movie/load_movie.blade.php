<div class="row">
    @forelse($movie_list as $movie)
        <div class="col-3">
            <div class="card" style="height: 370px">
                <div class="card-header pt-0 pb-0 text-right">
                    @if(Auth::user()->user_type == 'admin')
                        <a title="Edit" onclick="editMovie('{{$movie->id}}')" style="cursor: pointer;font-size: 22px" class="text-info mr-2 view_modal"><i class="fa fa-edit"></i></a>
                        <a title="Delete" onclick="deleteMovie('{{$movie->id}}')" style="cursor: pointer;font-size: 22px" class="text-danger mr-2"><i class="fa fa-trash"></i></a>
                    @endif

                    @php
                        //check period date validation
                        $now = date('Y-m-d H:i:s');
                        $valid = ($now >= $movie->rent_period_from) && ($now <= $movie->rent_period_to) ? 1 : 0;
                    @endphp

                        {{--
                              show movie condition check for basic user:
                             ->if rent the movie
                             ->period off validate time

                         --}}
                    @if((Auth::user()->subscription_type == 'basic' && count($movie->rents) && $valid ) || Auth::user()->subscription_type == 'premium' || Auth::user()->user_type == 'admin')
                            <a title="Show" onclick="showMovie('{{$movie->id}}')" style="cursor: pointer;font-size: 22px" class="text-green"><i class="fa fa-eye"></i></a>
                    @endif

                    {{--check rent price and period set or not--}}
                    @if(Auth::user()->subscription_type == 'basic' && $movie->rent_price && $movie->rent_period_from)
                        <a title="Rent"  onclick="rentMovie('{{$movie->id}}','{{$movie->rent_period_from}} - {{$movie->rent_period_to}}','{{$movie->rent_price}}')" style="cursor: pointer;font-size: 22px" class="text-warning"><i class="fa fa-cart-arrow-down"></i></a>
                    @endif

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="movie-img">
                        <img width="100%" height="200" src="{{$movie->poster}}">
                    </div>
                    <div class="movie-info">
                        <span><b>Title : </b> {{$movie->title}} </span>
                        <br>
                        <span><b>Cast : </b>
                           @php
                               $casts = json_decode($movie->casts,true)
                           @endphp
                           {{implode(',',$casts)}}
                        </span>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <h4 class="text-center">No data found</h4>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    @endforelse
</div>
@if(count($movie_list))
    <div class="row">
        <div class="col-12 text-center">
            <button onclick="loadData(8)" class="btn btn-success mb-2" type="button">Load More</button>
        </div>
    </div>
@endif


