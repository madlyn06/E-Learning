@if($isTownship)
    <select class="form-control">
        @foreach($townships as $township)
            <option @if($township_id == $township->id) selected @endif value="{{$township->id}}">{{ $township->name }}</option>
        @endforeach
    </select>
@else
    <select class="form-control">
        @foreach($districts as $district)
            <option @if($district_id == $district->id) selected @endif value="{{$district->id}}">{{ $district->name }}</option>
        @endforeach
    </select>
@endif
