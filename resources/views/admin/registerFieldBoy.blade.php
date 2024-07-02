<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register FieldBoy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <div class="container">
    
  <a href="{{route('admin.dashboard')}}" class="btn btn-secondary m-2">Home</a>
  <div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register Field Worker') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('fieldboy.register') }}">
                        @csrf

                         <!-- Name -->
                         <div class="form-group row my-2">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="form-group row my-2">
                            <label for="mobile" class="col-md-4 col-form-label text-md-right">{{ __('Mobile') }}</label>

                            <div class="col-md-6">
                                <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" required autocomplete="mobile">

                                @error('mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Aadhar -->
                        <div class="form-group row my-2">
                            <label for="aadhar" class="col-md-4 col-form-label text-md-right">{{ __('Aadhar') }}</label>

                            <div class="col-md-6">
                                <input id="aadhar" type="text" class="form-control @error('aadhar') is-invalid @enderror" name="aadhar" value="{{ old('aadhar') }}" required autocomplete="aadhar">

                                @error('aadhar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Pancard -->
                        <div class="form-group row my-2">
                            <label for="pancard" class="col-md-4 col-form-label text-md-right">{{ __('Pancard') }}</label>

                            <div class="col-md-6">
                                <input id="pancard" type="text" class="form-control @error('pancard') is-invalid @enderror" name="pancard" value="{{ old('pancard') }}" required autocomplete="pancard">

                                @error('pancard')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-group row my-2">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" required autocomplete="address">{{ old('address') }}</textarea>

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-group row my-2">
                            <label for="location" class="col-md-4 col-form-label text-md-right">{{ __('Location') }}</label>

                            <div class="col-md-6">
                                <select id="location" class="form-control @error('location') is-invalid @enderror" name="location" required>
                                    <option value="">Select Location</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location') == $location->id ? 'selected' : '' }}>{{ $location->district }} ( {{$location->state}} )</option>
                                    @endforeach
                                </select>

                                @error('location')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group row my-2 my-2 mb-0">
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
   </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
