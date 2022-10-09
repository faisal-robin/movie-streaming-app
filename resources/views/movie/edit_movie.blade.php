<div class="row mb-2">
    <div class="col-6">
        <input type="hidden" class="form-control" name="id" value="{{$movie_info->id}}">
        <label>Title :</label>
        <input readonly required="" type="text" class="form-control" name="title" id="title" placeholder="Title" value="{{$movie_info->title}}">
    </div>
    <div class="col-6">
        <label>Release Year :</label>
        <input readonly required="" type="text" class="form-control" name="release_year" id="release_year" placeholder="Release Year" value="{{$movie_info->release_year}}">
    </div>
</div>

<div class="row mb-2">
    <div class="col-6">
        <label>Tag :</label>
        <input required="" type="text" class="form-control" name="tag" id="tag" placeholder="Tag" value="{{$movie_info->tag}}">
    </div>
    <div class="col-6">
        <label>Rent Period :</label>
        <input autocomplete="off" required="" type="text" class="form-control date_range" name="rent_period" id="rent_period" placeholder="Rent Period" value="{{ $movie_info->rent_period_from ? $movie_info->rent_period_from.' - '.$movie_info->rent_period_to : ''}}">
    </div>

</div>

<div class="row mb-2">
    <div class="col-6">
        <label>Plan Type :</label>
        <select class="form-control" name="plan_type" id="plan_type">
            <option value="">Select Plan Type</option>
            <option @if($movie_info->plan_type == 'basic') selected @endif value="basic">Basic</option>
            <option @if($movie_info->plan_type == 'premium') selected @endif value="premium">Premium </option>
        </select>
    </div>
    <div class="col-6">
        <label>Rent Price :</label>
        <input required="" type="text" class="form-control" name="rent_price" id="rent_price" placeholder="Rent Price" value="{{$movie_info->rent_price}}">
    </div>
</div>
<script>
    $(function() {
        $('input[name="rent_period"]').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="rent_period"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('Y-M-DD HH:mm:ss') + ' - ' + picker.endDate.format('Y-M-DD HH:mm:ss'));
        });

        $('input[name="rent_period"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
