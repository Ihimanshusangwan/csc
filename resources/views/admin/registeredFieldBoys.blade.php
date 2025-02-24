<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fieldboys</title>
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
  </style>
</head>
<body>

  <div class="registered-staff-list-page">
    <div class="heading">
      <div class="dashboard-content">
        <span class="material-icons home-icon"> home </span>
        <h3 class="dashboard">Registered Field Boys Dashboard</h3>
      </div>
      
    </div>
  </div>
  <a href="{{route('admin.dashboard')}}" class="btn btn-secondary m-2">Home</a>
  <a href="{{route('admin.leaderboard')}}" class="btn btn-secondary m-2">Generate leaderboard</a>
  <div class="sort-filter">
    <div class="dropdowns">
    </div>
</div>
  <table class="table table-striped mt-4">
    <thead>
      <tr class="table-dark text-center">
        <th scope="col">Sr.No</th>
        <th scope="col">Name</th>
        <th scope="col">Date and Time of Registration</th>
        <th scope="col">Mobile</th>
        <th scope="col">Aadhar No.</th>
        <th scope="col">Pan No.</th>
        <th scope="col">Refferal Code</th>
        <th scope="col">City</th>
        <th scope="col">Address</th>
        <th scope="col">Referred Agents</th>
      </tr>
    </thead>
    <tbody>
      @php $counter = 1; @endphp @foreach($fieldboys as $fieldboy)
      <tr class="text-center">
        <th scope="row">{{ $counter++ }}</th>
        <td>{{ $fieldboy->name}}</td>
        <td>{{ $fieldboy->created_at}}</td>
        <td>{{ $fieldboy->mobile}}</td>
        <td>{{ $fieldboy->aadhar}}</td>
        <td>{{ $fieldboy->pancard}}</td>
        <td>{{ $fieldboy->referal_code}}</td>
        <td>{{ $fieldboy->city}}</td>
        <td>{{ $fieldboy->address}}</td>
        <td>{{ $fieldboy->referred_agent_count}}</td>
        
      </tr>
      @endforeach
    </tbody>
  </table>
  <div>     
        {{ $fieldboys->links('pagination::bootstrap-5') }}   
  </div>  
 
 <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>