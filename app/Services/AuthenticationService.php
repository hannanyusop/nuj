<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthenticationService
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Authenticate user and create session.
     */
    public function login(string $email, string $password, bool $remember = false): User
    {
        try {
            // Authenticate user
            $user = $this->userService->authenticateUser($email, $password);
            
            // Create session
            Auth::login($user, $remember);
            
            // Clear any logout marks
            $user->clearLogoutMark();
            
            Log::info('User logged in successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            return $user;
        } catch (ValidationException $e) {
            Log::warning('Login attempt failed', [
                'email' => $email,
                'ip' => request()->ip(),
                'errors' => $e->errors()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Login error', [
                'email' => $email,
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);
            throw new \Exception('Login failed: ' . $e->getMessage());
        }
    }

    /**
     * Logout user and clear session.
     */
    public function logout(): void
    {
        $user = Auth::user();
        
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => request()->ip()
            ]);
        }
        
        Auth::logout();
        Session::flush();
    }

    /**
     * Force logout user (admin action).
     */
    public function forceLogout(User $user): void
    {
        $this->userService->markUserForLogout($user);
        
        Log::info('User force logged out by admin', [
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
            'ip' => request()->ip()
        ]);
    }

    /**
     * Check if user is authenticated.
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Get current authenticated user.
     */
    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Check if current user is admin.
     */
    public function isAdmin(): bool
    {
        $user = $this->getCurrentUser();
        return $user && $user->isAdmin();
    }

    /**
     * Check if current user is staff.
     */
    public function isStaff(): bool
    {
        $user = $this->getCurrentUser();
        return $user && $user->isStaff();
    }

    /**
     * Check if current user is customer.
     */
    public function isCustomer(): bool
    {
        $user = $this->getCurrentUser();
        return $user && $user->isCustomer();
    }

    /**
     * Check if current user is runner.
     */
    public function isRunner(): bool
    {
        $user = $this->getCurrentUser();
        return $user && $user->isRunner();
    }

    /**
     * Validate user access to specific resource.
     */
    public function validateAccess(User $user, string $action): bool
    {
        // Admin has full access
        if ($user->isAdmin()) {
            return true;
        }

        // Staff can access staff-related actions
        if ($user->isStaff() && in_array($action, ['manage_parcels', 'manage_trips', 'view_reports'])) {
            return true;
        }

        // Customers can only access their own data
        if ($user->isCustomer() && in_array($action, ['view_own_parcels', 'update_own_profile'])) {
            return true;
        }

        // Runners can access runner-related actions
        if ($user->isRunner() && in_array($action, ['manage_pickups', 'update_trip_status'])) {
            return true;
        }

        return false;
    }

    /**
     * Get user's accessible offices.
     */
    public function getAccessibleOffices(User $user): \Illuminate\Database\Eloquent\Collection
    {
        if ($user->isAdmin()) {
            return \App\Models\Office::all();
        }

        if ($user->office_id) {
            return \App\Models\Office::where('id', $user->office_id)->get();
        }

        return collect();
    }

    /**
     * Refresh user session.
     */
    public function refreshSession(): void
    {
        $user = $this->getCurrentUser();
        
        if ($user) {
            // Check if user needs to be logged out
            if ($user->needsLogout()) {
                $this->logout();
                throw new \Exception('You have been logged out by an administrator.');
            }
            
            // Update last activity
            $user->updateLastLogin();
        }
    }

    /**
     * Get login statistics.
     */
    public function getLoginStats(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'today_logins' => User::where('last_login_at', '>=', $today)->count(),
            'this_week_logins' => User::where('last_login_at', '>=', $thisWeek)->count(),
            'this_month_logins' => User::where('last_login_at', '>=', $thisMonth)->count(),
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
        ];
    }

    /**
     * Send password reset link to user.
     */
    public function sendPasswordResetLink(string $email): void
    {
        $status = \Illuminate\Support\Facades\Password::sendResetLink(['email' => $email]);

        if ($status !== \Illuminate\Support\Facades\Password::RESET_LINK_SENT) {
            throw new \Exception(__($status));
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(string $email, string $password, string $token): void
    {
        $status = \Illuminate\Support\Facades\Password::reset(
            [
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
                'token' => $token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'password_changed_at' => now(),
                ])->save();
            }
        );

        if ($status !== \Illuminate\Support\Facades\Password::PASSWORD_RESET) {
            throw new \Exception(__($status));
        }
    }
} 