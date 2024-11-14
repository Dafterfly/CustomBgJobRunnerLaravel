<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background Job Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>Background Job Dashboard</h1>
        
        <!-- Display any success messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <h2>Job Logs</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Class</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log['id'] }}</td>
                        <td>{{ $log['class'] }}</td>
                        <td>{{ $log['method'] }}</td>
                        <td>{{ $log['status'] }}</td>
                        <td>{{ $log['retries'] }}</td>
                        <td>
                            <form action="{{ route('background-jobs.retry', $log['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning">Retry</button>
                            </form>
                            <form action="{{ route('background-jobs.cancel', $log['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
