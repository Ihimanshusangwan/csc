<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Staff</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary m-2">Home</a>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register Staff') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('staff.register') }}">
                            @csrf

                            <div class="form-group row mb-2">
                                <label for="userid"
                                    class="col-md-4 col-form-label text-md-right">{{ __('User Name') }}</label>
                                <div class="col-md-6">
                                    <input id="userid" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autofocus>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="mobile"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Mobile') }}</label>
                                <div class="col-md-6">
                                    <input id="mobile" type="text"
                                        class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                        value="{{ old('mobile') }}" required>
                                    @error('mobile')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="location"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Location') }}</label>
                                <div class="col-md-6">
                                    <select id="location" class="form-control @error('location') is-invalid @enderror"
                                        name="location" required>
                                        <option value="">Select Location</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->district }}</option>
                                        @endforeach
                                    </select>
                                    @error('location')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row mb-2">
                                <label for="servicegroup"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Service Group') }}</label>
                                @error('selected_services[]')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <!-- Accordions for Service Groups and Services -->
                                @foreach ($serviceGroups as $serviceGroup)
                                    <div class="accordion" id="serviceGroup{{ $serviceGroup->id }}">
                                        <div class="card">
                                            <div class="card-header" id="heading{{ $serviceGroup->id }}">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#collapse{{ $serviceGroup->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse{{ $serviceGroup->id }}">
                                                        {{ $serviceGroup->name }}
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="collapse{{ $serviceGroup->id }}" class="collapse"
                                                aria-labelledby="heading{{ $serviceGroup->id }}"
                                                data-parent="#serviceGroup{{ $serviceGroup->id }}">
                                                <div class="card-body">
                                                    @foreach ($services as $service)
                                                        @if ($service->service_group_id == $serviceGroup->id)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="selected_services[]"
                                                                    value="{{ $service->id }}">
                                                                <label class="form-check-label">
                                                                    {{ $service->name }}
                                                                </label>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- 
                                <div class="col-md-6">
                                    <select id="servicegroup"
                                        class="form-control @error('servicegroup') is-invalid @enderror"
                                        name="servicegroup" required>
                                        <option value="">Select Service Group</option>
                                        @foreach ($serviceGroups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('servicegroup')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}
                            </div>

                            <div class="form-group row mb-2 mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (Optional) -->

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
