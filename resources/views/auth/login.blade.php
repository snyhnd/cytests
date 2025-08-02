@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>ユーザーログイン画面</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- パスワード -->
        <div class="mb-3">
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password"
                   placeholder="パスワード">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div class="mb-3">
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="email"
                   placeholder="メールアドレス">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- ボタン -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">ログイン</button>
            <a class="btn btn-secondary" href="{{ route('register') }}">新規登録</a>
        </div>
    </form>
</div>
@endsection
