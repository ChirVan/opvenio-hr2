@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4"><i class="bx bx-shield me-2"></i>Security Settings</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Change Password</h5>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-success">Update Password</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Two-Factor Authentication</h5>
            <p class="card-text">Manage your two-factor authentication settings for enhanced account security.</p>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-success">Manage 2FA</a>
        </div>
    </div>
</div>
@endsection
