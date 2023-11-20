@extends('index')

@section('content')
    <div class="content">
        <div class="row content-row">
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
                    {!! Form::label('province', 'City', ['class' => 'col-4 form-label']) !!}
                    {!! Form::select('province', $filter['province'], $input['province'], [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('skill', 'Top 1 Language', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('skill', $filter['skill'], $input['skill'], [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('experience', 'Years Of Experience', ['class' => 'form-label col-4']) !!}
                    {!! Form::number('experience', $input['experience'], [
                        'class' => 'form-input col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('position', 'Position Level', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('position', $filter['position'], $input['position'], [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('english', 'English level', ['class' => 'form-label col-4']) !!}
                    {!! Form::select('english', $filter['english'], $input['english'], [
                        'class' => 'form-select col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>
                <div class="form-group row">
                    {!! Form::label('salary', 'Salary', ['class' => 'form-label col-4']) !!}
                    {!! Form::number('salary', $input['salary'], [
                        'class' => 'form-input col-8',
                        'placeholder' => 'Select an option',
                    ]) !!}
                </div>


                <button class="form-button"><i class="fa fa-search"></i></button>
                {!! Form::close() !!}

            </div>
            @if (request()->route()->getName() == 'talents.list')
                <div class="container-list col-7">
                    <div class="list-title">
                        <h4>Available Talents: </h4>
                        <h4 style="color: #c43e1c;" class="ml-3">37</h4>
                    </div>
                    <ul class="list-unstyled">
                        <li class="talent">
                            <img src="{{ asset('images/avatar-man.png') }}" alt="avatar" class="talent-avatar">
                            <a class="talent-btn " href="{{ route('talents.detail') }}"><i class="fa fa-search"></i></a>
                            <div class="talent-body">
                                <h5 class="mt-0 mb-1">Mr. Nguyen Thanh Long</h5>
                                Zalo - Ho Chi Minh City, Viet Nam
                            </div>
                        </li>
                      
                        <li class="talent">
                            <div class="talent-body mt-4">
                                <a href=""> View more ... </a>
                            </div>

                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

@endsection

{{-- ---------------------------------------------------------------- --}}

@push('scripts')

    @if (request()->route()->getName() == 'talents.list')
        <script>
            $(document).ready(function() {
                        $('.content').css("background-image", 'none');
                        console.log("list");
                    });
        </script>
    @endif
@endpush
