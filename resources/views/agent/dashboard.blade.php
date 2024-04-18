<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-4K6EYYCJDR"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag("js", new Date());

    gtag("config", "G-4K6EYYCJDR");
  </script>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./agent-dashboard.css" />
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <title>Dashboard</title>
  <script src="{{asset('js/sweetAlert.js')}}"></script>
  <style media="screen">
    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .gradient {
      background: linear-gradient(90deg, #132247 0%, #b8b2b2 100%);
    }

    .lap {
      background: black;
      color: white;
    }

    @media (max-width: 767px) {
      .navicon {
        width: 1.125em;
        height: .125em;
      }

      .navicon::before,
      .navicon::after {
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        transition: all .2s ease-out;
        content: '';
        background: #3D4852;
      }

      .navicon::before {
        top: 5px;
      }

      .navicon::after {
        top: -5px;
      }

      .menu-btn:not(:checked)~.menu {
        display: none;
      }

      .menu-btn:checked~.menu {
        display: block;
      }

      .menu-btn:checked~.menu-icon .navicon {
        background: transparent;
      }

      .menu-btn:checked~.menu-icon .navicon::before {
        transform: rotate(-45deg);
      }

      .menu-btn:checked~.menu-icon .navicon::after {
        transform: rotate(45deg);
      }

      .menu-btn:checked~.menu-icon .navicon::before,
      .menu-btn:checked~.menu-icon .navicon::after {
        top: 0;
      }
    }

    .background-points {
      background-color: white;
      /* width: 3rem; */
      border-radius: 2rem;
    }

    .points {
      color: green;
      font-family: sans-serif;
      font-weight: bolder;
      font-size: 15px;
      text-align: center;
      padding: 0.3rem 0.5rem;
    }

    body {
      height: 100%;
      width: 100%;
      /* padding: 1rem 1rem; */
      background: linear-gradient(to right, #e9dfc4 0%, #e9dfc4 1%, #ede3c8 2%, #ede3c8 24%, #ebddc3 25%, #e9dfc4 48%, #ebddc3 49%, #e6d8bd 52%, #e6d8bd 53%, #e9dbc0 54%, #e6d8bd 55%, #e6d8bd 56%, #e9dbc0 57%, #e6d8bd 58%, #e6d8bd 73%, #e9dbc0 74%, #e9dbc0 98%, #ebddc3 100%);
      background-size: 120px;
      background-repeat: repeat;
    }

    .search {
      width: 30%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: row;
    }

    /* Dropdown Button */
    .dropbtn {
      background-color: #04AA6D;
      color: white;
      padding: 16px;
      font-size: 16px;
      border: none;
    }

    /* The container <div> - needed to position the dropdown content */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    /* Dropdown Content (Hidden by Default) */
    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      width: 100%;
      min-width: 300px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    /* Links inside the dropdown */
    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      width: 100%;
    }

    /* Change color of dropdown links on hover */
    .dropdown-content a:hover {
      background-color: #ddd;
    }

    /* Show the dropdown menu on hover */
    .dropdown:hover .dropdown-content {
      display: block;
    }

    /* Change the background color of the dropdown button when the dropdown content is shown */
    .dropdown:hover .dropbtn {
      background-color: #3e8e41;
    }

    .sections {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      flex-direction: row;
      gap: 1rem;
    }
  </style>
</head>

<body>
  <nav class="nav gradient flex flex-wrap items-center justify-between px-4 lap">
    <div class="flex flex-no-shrink items-center mr-6 py-3 text-grey-darkest">
      <span class="font-semibold text-3xl tracking-tight"><span>E-Seva Kendra</span></span>
    </div>

    <input class="menu-btn hidden" type="checkbox" id="menu-btn" />
    <label class="menu-icon block cursor-pointer md:hidden px-2 py-4 relative select-none" for="menu-btn">
      <span class="navicon bg-grey-darkest flex items-center relative"></span>
    </label>

    <ul class="menu border-b md:border-none flex justify-end list-reset m-0 w-full md:w-auto">
      <li class="border-t md:border-none px-4 py-3">
        <div class="background-points">
          <div class="points">Balance: &#8377; {{$balance}}</div>
        </div>
      </li>
      <li class="border-t md:border-none px-4 py-3">
        <div class="background-points">
          <div class="points">Earnings: &#8377; {{$sumOfPrices}}</div>
        </div>
      </li>
      <li class="border-t md:border-none">
        <a href="{{route('agent.applications',[ 'category' => 'all'])}}"
          class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker">Dashboard</a>
      </li>
      <li class="border-t md:border-none">
        <a href="{{route('agent.recharge-history')}}"
          class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker">Recharges</a>
      </li>

      <li class="border-t md:border-none">
        <a href="{{ route('agent.profile');}}"
          class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker">My Profile</a>
      </li>

      <li class="border-t md:border-none">
        <a href="{{route('agent.logout')}}"
          class="block md:inline-block px-4 py-3 no-underline text-grey-darkest hover:text-grey-darker">Logout</a>
      </li>
    </ul>
  </nav>

  <div class="container-fluid mt-4">
    <div class="row">
    <div class="col-4">
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000 // Close the alert after 3 seconds
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000 // Close the alert after 3 seconds
            });
        </script>
    @endif
</div>


      <div class="col-4">
        <h3 class="text-center">Services</h3>
      </div>
      <div class="col-4">
        <div class="row">
          <div class="col-md-12 mb-4">
              <input class="form-control me-2" id="searchInput" type="search" placeholder="Search here ..." aria-label="Search" onkeyup="searchServices()">
              <div class="dropdown-menu" aria-labelledby="servicesDropdown" id="servicesDropdown" style="display: none;">
                  <!-- Dropdown items will be dynamically added here -->
              </div>
          </div>
      </div>
      </div>
    </div>


    <div class="container">
      <div class="row">
        @php
        // Initialize an empty array to store services with their routes
        $servicesArray = [];
    
        @endphp
    
        @foreach($serviceGroups as $serviceGroup)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset($serviceGroup['group_photo']) }}" class="card-img-top"
                    alt="{{ $serviceGroup['group_name'] }} Photo" style="width: 100%; height: 300px;">
                <div class="card-body" style="height: 100px;"> <!-- Adjust height as needed -->
                    <h5 class="card-title">{{ $serviceGroup['group_name'] }}</h5>
                    <div class="sections">
                        <a href="{{ route('service-group.view', ['serviceGroupId' => $serviceGroup['group_id']]) }}"
                            class="btn btn-success">View</a>
                        <div class="dropdown">
                            <button class="btn btn-warning" type="button"
                                id="{{ 'servicesDropdown_' . $serviceGroup['group_id'] }}" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">Services</button>
                            <div class="dropdown-menu"
                                aria-labelledby="{{ 'servicesDropdown_' . $serviceGroup['group_id'] }}">
                                @foreach($serviceGroup['services'] as $service)
                                @php
                                // Store service details along with its route in the $servicesArray
                                $servicesArray[] = [
                                'name' => $service->name,
                                'route' => route('service.direct-apply', ['id' => $service->id]),
                                ];
                                @endphp
                                <a class="dropdown-item"
                                    href="{{ route('service.direct-apply', ['id' => $service->id]) }}">
                                    <h6>{{ $service->name }}</h6>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    
    </div>
  </div>
  <script>
    // Store services array in a JavaScript variable
    var servicesArray = {!! json_encode($servicesArray ?? []) !!};

    // Function to perform live search
    function searchServices() {
        var input, filter, dropdown, items, a, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        dropdown = document.getElementById("servicesDropdown");
        dropdown.innerHTML = ""; // Clear previous search results
        items = [];

        // Filter and display matching items
        for (i = 0; i < servicesArray.length; i++) {
            txtValue = servicesArray[i].name.toUpperCase();
            if (txtValue.indexOf(filter) > -1) {
                var item = document.createElement("a");
                item.className = "dropdown-item";
                item.innerHTML = "<h6>" + servicesArray[i].name + "</h6>";
                // Use a closure to capture the current value of 'route'
                item.onclick = (function(route) {
                    return function() {
                        redirectToService(route);
                    };
                })(servicesArray[i].route);
                dropdown.appendChild(item);
                items.push(item); // Store reference to the added item
            }
        }

        // Show or hide the dropdown based on search results
        dropdown.style.display = (items.length > 0) ? "block" : "none";
    }

    // Function to handle click event on dropdown item
    function redirectToService(route) {
        window.location.href = route;
    }
</script>


</body>

</html>