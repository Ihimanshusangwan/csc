<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Leaderboard</title>
  <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <style>
    body {
      height: 100%;
      width: 100%;
      background: linear-gradient(to right,
          #e9dfc4 0%,
          #e9dfc4 1%,
          #ede3c8 2%,
          #ede3c8 24%,
          #ebddc3 25%,
          #e9dfc4 48%,
          #ebddc3 49%,
          #e6d8bd 52%,
          #e6d8bd 53%,
          #e9dbc0 54%,
          #e6d8bd 55%,
          #e6d8bd 56%,
          #e9dbc0 57%,
          #e6d8bd 58%,
          #e6d8bd 73%,
          #e9dbc0 74%,
          #e9dbc0 98%,
          #ebddc3 100%);
      background-size: 120px;
      background-repeat: repeat;
    }

    .registered-staff-list-page {
      padding: 1rem 1rem;
      height: 100%;
      width: 100%;
    }

    .heading {
      /* width: 100%; */
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-direction: row;
    }

    .total-count {
      margin-top: 2rem;
    }

    .dashboard-content {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: row;
    }

    .background-points {
      background-color: white;
      /* width: 3rem; */
      border-radius: 2rem;
      margin-right: 3rem;
      cursor: pointer;
    }

    .earned-points {
      color: green;
      font-family: sans-serif;
      font-weight: bolder;
      font-size: 15px;
      text-align: center;
      padding: 0.3rem 0.5rem;
    }

    .pending-points {
      /* color: green; */
      font-family: sans-serif;
      font-weight: bolder;
      font-size: 15px;
      text-align: center;
      padding: 0.3rem 0.5rem;
    }

    .home-icon {
      margin-bottom: 0.5rem;
    }

    a {
      text-decoration: none;
    }

    .dropdowns {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: row;
    }

    .sort-filter {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-direction: row;
      padding: 0rem 2rem;
    }

    .total-count {
      margin-top: 2rem;
    }
    .leaderboard-table {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .leaderboard-header {
            background-color: #343a40;
            color: #ffffff;
        }
        .leaderboard-table th, .leaderboard-table td {
            vertical-align: middle;
        }
  </style>
</head>
<body>

  <div class="registered-staff-list-page">
    <div class="heading">
      <div class="dashboard-content">
        <span class="material-icons home-icon"> home </span>
        <h3 class="dashboard"> Field Boys Leaderboard</h3>
      </div>
      
    </div>
  </div>
  <a href="{{route('admin.dashboard')}}" class="btn btn-secondary m-2">Home</a>
  <a href="{{route('admin.registered-fieldboy')}}" class="btn btn-secondary m-2">Back</a>
  <div class="sort-filter">
    <div class="dropdowns">
    </div>
</div>
<div class="container mt-5">
    <h1 class="text-center mb-4">Leaderboard</h1>

    <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->get('start_date') }}">
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->get('end_date') }}">
        </div>
        <div class="col-md-4">
          <label for="services" class="form-label">Location:</label>
          <select class="form-control" id="location" name="location">
              <option value="" selected>All</option>
              @foreach ($locations as $location)
                  <option value="{{ $location->id }}"
                      {{ request('location') == $location->id ? 'selected' : '' }}>{{ $location->district }} ({{$location->state}})
                  </option>
              @endforeach
          </select>
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>

    @if ($fieldboys->count())
        <div class="table-responsive">
            <table class="table table-striped leaderboard-table">
                <thead class="leaderboard-header">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">City</th>
                        <th scope="col">Referred Agents</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fieldboys as $fieldboy)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $fieldboy->name }}</td>
                            <td>{{ $fieldboy->mobile }}</td>
                            <td>{{ $fieldboy->city }}</td>
                            <td>{{ $fieldboy->referred_agent_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            No results found for the selected date range.
        </div>
    @endif
</div>
 
 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>