@extends('index')

@section('content')
    <div class="content">
        <div class="row">
            <div class="container-filter col-4">
                <h4>Search by:</h4>
                <!-- Display the success message if any -->
                @if (session('success'))
                    <div class="form-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Display the form errors if any -->
                @if ($errors->any())
                    <div class="form-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Create the form using Laravel collective -->
                {!! Form::open(['route' => 'lead.list', 'method' => 'get', 'class' => 'form-horizontal']) !!}

                <div class="form-group row">
                    {!! Form::label('city', 'City', ['class' => 'col-4 form-label']) !!}
                    {!! Form::select('city', $filter['city'], null, [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('ability', 'Top 1 Language', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('ability', $filter['ability'], null, [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('experience', 'Years Of Experience', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('city', $filter['experience'], null, [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('position', 'Position Level', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('city', $filter['position'], null, [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('english', 'English level', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('city', $filter['english'], null, [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('salary', 'Salary', ['class' => 'form-label col-4']) !!}
                    {!! Form::number('salary', 'Select an option', [
                        'class' => 'form-input col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>


                <button class="form-button"><i class="fa fa-search"></i></button>
                {!! Form::close() !!}
            </div>
            <div class="container-list col-7">
                <div class="list-title">
                    <h4>Available Talents: </h4>
                    <h4 style="color: #c43e1c;" class="ml-3">37</h4>
                </div>
                <ul class="list-unstyled">
                    <li class="talent">
                        <img src="{{ asset('images/avatar-man.png') }}" alt="avatar" class="talent-avatar">
                        <button class="talent-btn"><i class="fa fa-search"></i></button>
                        <div class="talent-body">
                            <h5 class="mt-0 mb-1">Mr. Nguyen Thanh Long</h5>
                            Zalo - Ho Chi Minh City, Viet Nam
                        </div>
                    </li>
                    <li class="talent my-4">
                        <img src="{{ asset('images/avatar-man.png') }}" alt="avatar" class="talent-avatar"">
                        <button class="talent-btn"><i class="fa fa-search"></i></button>
                        <div class="talent-body">
                            <h5 class="mt-0 mb-1">Mr. Diep Nhat Thu</h5>
                            Facebook - Ho Chi Minh City, Viet Nam
                        </div>
                    </li>
                    <li class="talent">
                        <img src="{{ asset('images/avatar-man.png') }}" alt="avatar" class="talent-avatar"">
                        <button class="talent-btn"><i class="fa fa-search"></i></button>
                        <div class="talent-body">
                            <h5 class="mt-0 mb-1">Ms.Nguyen Thu Ha </h5>
                            Tiki - Ho Chi Minh City, Viet Nam
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    {{-- ---------------------------------------------------------------- --}}


@stop