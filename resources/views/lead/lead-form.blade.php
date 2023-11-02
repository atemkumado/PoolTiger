<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-input {
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    .form-select {
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        appearance: none;
    }

    .form-button {
        display: block;
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    .form-button:hover {
        background-color: #0069d9;
    }

    .form-error {
        color: red;
        font-weight: bold;
    }

    .form-success {
        color: green;
        font-weight: bold;
    }

    /* Add some media queries for responsiveness */
    @media (max-width: 480px) {
        .container {
            width: 90%;
        }
    }
</style>

<div class="container"  style="background-color: white ">
    <h1>Responsive Form</h1>
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
    {!! Form::open(['route' => 'lead.filter', 'method' => 'post']) !!}
    <div class="form-group">
        {!! Form::label('name', 'Name', ['class' => 'form-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-input', 'placeholder' => 'Enter your name']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('email', 'Email', ['class' => 'form-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-input', 'placeholder' => 'Enter your email']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('province', 'Province', ['class' => 'form-label']) !!}
        {!! Form::select('province', $provinces, null, [
            'class' => 'form-select',
            'placeholder' => 'Select your country',
        ]) !!}
    </div>
    <div class="form-group">
        {!! Form::submit('Submit', ['class' => 'form-button']) !!}
    </div>
    {!! Form::close() !!}
</div>
