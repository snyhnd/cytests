@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>ユーザー新規登録画面</h2>

    {{-- エラーメッセージの表示 --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- ユーザー名 -->
        <div class="mb-3">
            <input id="name" type="text"
                class="form-control @error('name') is-invalid @enderror"
                name="name" value="{{ old('name') }}" required autocomplete="name"
                placeholder="ユーザー名">
            @error('name')
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

        <!-- パスワード -->
        <div class="mb-3">
            <input id="password" type="password"
                class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password"
                placeholder="パスワード">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- パスワード確認 -->
        <div class="mb-3">
            <input id="password-confirm" type="password"
                class="form-control"
                name="password_confirmation" required autocomplete="new-password"
                placeholder="パスワード（確認）">
        </div>

        <!-- ボタン -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">新規登録</button>
            <a class="btn btn-secondary" href="{{ route('login') }}">戻る</a>
        </div>
    </form>
</div>
@endsection