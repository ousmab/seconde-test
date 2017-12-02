@extends('layouts.master')

@section('content')
        <h1>The best url Shortner !!!</h1>
        
        
            {!! $errors->first('url','<small class="error-msg">:message</small>') !!}
        <form  method="POST">
            {{ csrf_field() }}
            <input type="text" name="url" placeholder="Entrez votre Url originale ici" value="{{ old('url') }}">
            <input type="submit" value="générer">
        </form>

        @if(!empty($infos))
           voici votre short Url <br>
           <input type="text" value="{{ config('app.url').'/'.$infos['shortened'] }}">
        @endif
@endsection