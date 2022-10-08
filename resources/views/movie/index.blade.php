@extends('layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h4 class="float-left">Movie List</h4>
            {{--check admin user--}}
            @if(Auth::user()->user_type == 'admin')
                <button onclick="importMovie()" type="button" class="float-right btn  btn-info btn-flat"><i
                        class="fas fa-file-import"></i> Import OMDB Movies
                </button>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="row">
            <div class="col-8">
                <div class="search m-2">
                    <div class="row">
                        <div class="col-6">
                            <input class="form-control" type="text" id="search-movie"
                                   placeholder="Enter movie tags/title">
                        </div>
                        <div class="col-2">
                            <button onclick="loadData()" class="btn btn-info form-control" type="button">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="load-movie-data"></div>


    <!-- Edit Modal -->
    <div class="modal fade bd-example-modal-lg" id="editModal" tabindex="-1" role="dialog"
         aria-labelledby="editModalTable" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTable">Edit Movie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form autocomplete="off" id="edit_form">
                    <div id="modal_body" class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button onclick="updateMovie()" type="button" class="btn btn-primary edit_button">Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            if ($(".load-movie-data").length > 0) {
                loadData();
            }
        });

        function loadData(limit='') {

            $('div.content-wrapper').block({
                message: '<h4>Loading...</h4>',
                css: { border: '3px solid blue' }
            });

            search_value = $('#search-movie').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{route('movies.load')}}",
                data: {search_value,limit},
                success: function (data, textStatus, jqXHR) {
                    $('div.content-wrapper').unblock();
                    $(".load-movie-data").html(data);
                }
            });
        }

        //show movie
        function showMovie(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{route('movies.show-movie')}}",
                data: {id},
                success: function (data, textStatus, jqXHR) {
                    if (data.status == 'error') {
                        Swal.fire(data.msg)
                    } else {
                        $('.container-fluid').html(data);
                    }

                }
            });
        }

        //load edit movie info
        function editMovie(id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{route('movies.edit')}}",
                data: {id},
                success: function (data, textStatus, jqXHR) {
                    $("#modal_body").html(data);
                    $("#editModal").modal("show");

                }
            });
        }

        //import movie
        function importMovie() {
            Swal.fire({
                title: 'Do you want to import movie?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('div.content-wrapper').block({
                        message: '<h4>Loading...</h4>',
                        css: { border: '3px solid blue' }
                    });
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "Post",
                        url: "{{route('movies.import')}}",
                        data: {},
                        type: 'json',
                        success: function (data, textStatus, jqXHR) {
                            //check status success
                            if (data.status == 'success') {
                                Swal.fire(data.msg)
                                location.reload();
                            } else {
                                Swal.fire(data.msg)
                            }
                        }
                    });
                }
            })

        }

        //delete movie
        function updateMovie() {
            var data = new FormData($('#edit_form')[0]);

            var id = $('[name=id]').val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{route('movies.update')}}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function (data, textStatus, jqXHR) {
                if (data.status == 'success') {
                    Swal.fire(data.msg)
                    location.reload();
                } else {
                    Swal.fire(data.msg)
                }
                // location.reload();
            }).fail(function (data, textStatus, jqXHR) {
                var json_data = JSON.parse(data.responseText);
                $.each(json_data.errors, function (edit_key, value) {
                    $("#" + edit_key).after("<span class='error_msg' style='color: red;font-weigh: 600'>" + value + "</span>");
                });
            });

        }

        //rent movie
        function rentMovie(id,rent_period,rent_price) {
            Swal.fire({
                title: 'Do you want to rent this movie?',
                html: '<b>Rent Period :</b>'+rent_period+'<br>'+'<b>Rent Price :</b>'+rent_price,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "Post",
                        url: "{{route('movies.rent')}}",
                        data: {id},
                        type: 'json',
                        success: function (data, textStatus, jqXHR) {
                            //check status success
                            if (data.status == 'success') {
                                Swal.fire(data.msg)
                                location.reload();
                            } else {
                                Swal.fire(data.msg)
                            }
                        }
                    });
                }
            })

        }

        //delete movie
        function deleteMovie(id) {
            Swal.fire({
                title: 'Do you want to delete this movie?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "Post",
                        url: "{{route('movies.delete')}}",
                        data: {id},
                        type: 'json',
                        success: function (data, textStatus, jqXHR) {
                            //check status success
                            if (data.status == 'success') {
                                Swal.fire(data.msg)
                                location.reload();
                            } else {
                                Swal.fire(data.msg)
                            }
                        }
                    });
                }
            })

        }
    </script>

    <!-- /.box -->
@endsection
