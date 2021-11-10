@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('includes.message')
            
            @foreach($images as $image)
              @include('includes.image',['images' => $image])
            @endforeach
             <!-- paginacion-->
            <div class="clearfix"></div>
            <p>{{$images->links()}}</p>
        </div>  
    </div>
</div>
@endsection
