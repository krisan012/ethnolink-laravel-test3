<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@1.*/css/pico.min.css">
    <title>TEST 2 laravel</title>

    <style>
        .alert {color:red}
    </style>
</head>
<body>
    <div class="container">
        <form action="{{ route('fetch_data') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="url">URL:</label>
                <input type="text" class="form-control" id="url" name="url" value="https://dummyjson.com/users" required>
            </div>
            <button type="submit" class="btn btn-primary" name="fetch">Fetch</button>
        </form>

        @if(isset($errorMessage) && !empty($errorMessage))
            <div class="alert" role="alert">
                API Error: {{ $errorMessage }}
            </div>
        @endif

        @if(isset($missingFields) && count($missingFields) > 0 )
            <div class="alert" role="alert">
                The following fields are missing from the API: {{ implode(', ', $missingFields) }}
            </div>
        @endif

        @isset($user, $randomIndex)
            <div>
                Total Records: {{ $totalRecord }} <br>
                Fetched Record Number: {{ $randomIndex + 1 }} <br>
                Average Age: {{ number_format($averageAge, 2) }} <br>
                Domain Extensions Count:
                <ul>
                    @foreach($emailDomainCount as $domain => $count)
                    <li>
                        {{ $domain }} ({{ $count }})
                    </li>
                    @endforeach
                </ul>
            </div>
        @endisset

        @if(isset($user) && empty($errorMessage))
            <form>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" value="{{ $user['firstName'] . ' ' . $user['lastName'] }}">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" value="{{ $user['email'] }}">
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" class="form-control" id="age" value="{{ $user['age'] }}">
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="text" class="form-control" id="gender" value="{{ $user['gender'] }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" id="phone" value="{{ $user['phone'] }}">
                </div>
            </form>
        @endif
    </div>
</body>
</html>