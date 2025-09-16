@extends('layouts.front-app')

@section('content')
<main>
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side">
                        <p class="text-white h2">MAGIC IS IN THE DETAILS</p>
                        <p class="white mb-0">Please use your credentials to login.<br>If you are not a member, please <a href="#" class="white">register</a>.</p>
                    </div>
                    <div class="form-side">
                        <a href="javascript:;"><span class="logo-single"></span></a>
                        <h6 class="mb-4">Login</h6>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <label class="form-group has-float-label mb-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <span>E-mail</span>
                            </label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <label class="form-group has-float-label mb-4">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <span>Password</span>
                                <i id="togglePassword" 
                                   class="fa fa-eye position-absolute" 
                                   style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                            </label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-primary btn-lg btn-shadow" type="submit">LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;

        // toggle eye / eye-slash icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
@endsection
