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
                    {!! Form::open(['route' => 'talents.list', 'method' => 'get', 'class' => 'form-horizontal']) !!}

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
                        {!! Form::number('salary', null, [
                            'class' => 'form-input col-8',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                    <div class="form-group row">
                        {!! Form::label('position', 'Position Level', ['class' => 'form-label col-4']) !!}
                        {!! Form::select('position', $filter['position'], null, [
                            'class' => 'form-select col-8',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                    <div class="form-group row">
                        {!! Form::label('english', 'English level', ['class' => 'form-label col-4']) !!}
                        {!! Form::select('english', $filter['english'], null, [
                            'class' => 'form-select col-8',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
                    <div class="form-group row">
                        {!! Form::label('salary', 'Salary', ['class' => 'form-label col-4']) !!}
                        {!! Form::number('salary', null, [
                            'class' => 'form-input col-8',
                            'placeholder' => 'Select an option',
                        ]) !!}
                    </div>
    
    
                    <button class="form-button"><i class="fa fa-search"></i></button>
                    {!! Form::close() !!}
                </div>

            </div>
        </div>


        {{--------------------------------------------------------------------}}


@stop
