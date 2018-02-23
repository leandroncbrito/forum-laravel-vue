@extends('layouts.app') 

@section('header')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a new thread</div>
                <div class="panel-body">
                    @auth
                    <form action="/threads" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="channel_id">Choose a channel:</label>
                            <select name="channel_id" id="channel_id" class="form-control" required>
                                <option value="">Choose One...</option>
                                @foreach ($channels as $channel)
                                    <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                        {{ $channel->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">Body:</label>
                            <wysiwyg name="body"></wysiwyg>
                            {{--  <textarea name="body" id="body" class="form-control" rows="8" value="{{ old('body') }}" required></textarea>  --}}
                        </div>

                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="6Ld82UcUAAAAAFgWcitl8ijLH3wQyClbfHtEQ5RA"></div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </div>
                        @if (count($errors))                        
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </form>
                    @else
                    <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection