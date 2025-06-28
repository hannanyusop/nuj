<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private AuthenticationService $authService,
        private UserService $userService
    ) {}

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        // Redirect if already authenticated
        if ($this->authService->isAuthenticated()) {
            return $this->redirectBasedOnUserType();
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login(
                $request->email,
                $request->password,
                $request->boolean('remember')
            );

            // Redirect based on user type
            return $this->redirectBasedOnUserType($user);

        } catch (ValidationException $e) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Login error in controller', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Login failed. Please try again.']);
        }
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been successfully logged out.');
    }

    /**
     * Show customer login form.
     */
    public function showCustomerLoginForm()
    {
        // Redirect if already authenticated as customer
        if ($this->authService->isAuthenticated() && $this->authService->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        return view('auth.customer-login');
    }

    /**
     * Handle customer login request.
     */
    public function customerLogin(LoginRequest $request)
    {
        try {
            $user = $this->authService->login(
                $request->email,
                $request->password,
                $request->boolean('remember')
            );

            // Ensure user is a customer
            if (!$user->isCustomer()) {
                $this->authService->logout();
                throw new \Exception('Access denied. This login is for customers only.');
            }

            return redirect()->route('customer.dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');

        } catch (ValidationException $e) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Customer login error', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => $e->getMessage()]);
        }
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm()
    {
        if ($this->authService->isAuthenticated()) {
            return $this->redirectBasedOnUserType();
        }

        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(RegisterRequest $request)
    {
        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'password' => $request->password,
                'type' => 'user', // Default to customer
            ];

            $user = $this->userService->createUser($userData);

            // Auto-login after registration
            $this->authService->login($user->email, $request->password);

            return redirect()->route('customer.dashboard')
                ->with('success', 'Account created successfully! Welcome to our platform.');

        } catch (\Exception $e) {
            Log::error('Registration error', [
                'error' => $e->getMessage(),
                'data' => $request->except('password', 'password_confirmation')
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['email' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Show password reset form.
     */
    public function showPasswordResetForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $this->authService->sendPasswordResetLink($request->email);

            return back()->with('success', 'Password reset link has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Password reset link error', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return back()->withErrors(['email' => 'Unable to send password reset link.']);
        }
    }

    /**
     * Show password reset form with token.
     */
    public function showPasswordResetFormWithToken(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    /**
     * Reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $this->authService->resetPassword(
                $request->email,
                $request->password,
                $request->token
            );

            return redirect()->route('login')
                ->with('success', 'Password has been reset successfully. You can now login with your new password.');
        } catch (\Exception $e) {
            Log::error('Password reset error', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Password reset failed. Please try again.']);
        }
    }

    /**
     * Redirect user based on their type.
     */
    private function redirectBasedOnUserType($user = null): \Illuminate\Http\RedirectResponse
    {
        $user = $user ?? auth()->user();

        return match($user->type) {
            'admin' => redirect()->route('admin.dashboard'),
            'manager', 'staff' => redirect()->route('staff.dashboard'),
            'runner' => redirect()->route('runner.dashboard'),
            'user' => redirect()->route('customer.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }
} 