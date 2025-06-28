<?php

namespace App\Services;

use App\Models\User;
use App\Models\Office;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        DB::beginTransaction();
        
        try {
            // Validate required fields
            $this->validateUserData($data);
            
            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            
            // Set default values
            $data['type'] = $data['type'] ?? User::TYPE_USER;
            $data['active'] = $data['active'] ?? 1;
            $data['wallet'] = $data['wallet'] ?? 0.00;
            $data['wallet_total'] = $data['wallet_total'] ?? 0.00;
            
            $user = User::create($data);
            
            // Assign default role based on type
            $this->assignDefaultRole($user);
            
            DB::commit();
            
            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create user', ['error' => $e->getMessage(), 'data' => $data]);
            throw new \Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data): User
    {
        DB::beginTransaction();
        
        try {
            // Validate data
            $this->validateUserData($data, $user->id);
            
            // Hash password if provided
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                $data['password_changed_at'] = now();
            }
            
            $user->update($data);
            
            DB::commit();
            
            Log::info('User updated successfully', ['user_id' => $user->id]);
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update user', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw new \Exception('Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete a user (soft delete).
     */
    public function deleteUser(User $user): bool
    {
        try {
            $user->delete();
            
            Log::info('User deleted successfully', ['user_id' => $user->id]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete user', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            throw new \Exception('Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Authenticate user login.
     */
    public function authenticateUser(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => ['Your account has been deactivated.'],
            ]);
        }
        
        if ($user->needsLogout()) {
            throw ValidationException::withMessages([
                'email' => ['You have been logged out by an administrator.'],
            ]);
        }
        
        // Update last login information
        $user->updateLastLogin();
        
        Log::info('User authenticated successfully', ['user_id' => $user->id, 'email' => $user->email]);
        
        return $user;
    }

    /**
     * Get customers with pagination.
     */
    public function getCustomers(int $perPage = 15, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = User::customers()->with(['office', 'defaultDropPoint']);
        
        // Apply filters
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone_number', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        if (isset($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }
        
        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get staff members with pagination.
     */
    public function getStaff(int $perPage = 15, array $filters = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = User::staff()->with(['office']);
        
        // Apply filters
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['office_id'])) {
            $query->where('office_id', $filters['office_id']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Update user wallet.
     */
    public function updateWallet(User $user, float $amount, string $type = 'add'): User
    {
        DB::beginTransaction();
        
        try {
            $currentWallet = (float) $user->wallet;
            $currentTotal = (float) $user->wallet_total;
            
            if ($type === 'add') {
                $newWallet = $currentWallet + $amount;
                $newTotal = $currentTotal + $amount;
            } else {
                $newWallet = $currentWallet - $amount;
                $newTotal = $currentTotal; // Total doesn't decrease
            }
            
            if ($newWallet < 0) {
                throw new \Exception('Insufficient wallet balance');
            }
            
            $user->update([
                'wallet' => $newWallet,
                'wallet_total' => $newTotal,
            ]);
            
            DB::commit();
            
            Log::info('User wallet updated', [
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'new_balance' => $newWallet
            ]);
            
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update user wallet', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'amount' => $amount
            ]);
            throw $e;
        }
    }

    /**
     * Mark user for logout.
     */
    public function markUserForLogout(User $user): void
    {
        $user->markForLogout();
        
        Log::info('User marked for logout', ['user_id' => $user->id]);
    }

    /**
     * Clear user logout mark.
     */
    public function clearUserLogoutMark(User $user): void
    {
        $user->clearLogoutMark();
        
        Log::info('User logout mark cleared', ['user_id' => $user->id]);
    }

    /**
     * Validate user data.
     */
    private function validateUserData(array $data, ?int $userId = null): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email' . ($userId ? ",$userId" : ''),
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'type' => 'nullable|in:' . implode(',', [
                User::TYPE_ADMIN,
                User::TYPE_MANAGER,
                User::TYPE_STAFF,
                User::TYPE_RUNNER,
                User::TYPE_USER
            ]),
            'office_id' => 'nullable|exists:offices,id',
            'default_drop_point' => 'nullable|exists:offices,id',
            'password' => 'nullable|string|min:8',
        ];
        
        $validator = \Illuminate\Support\Facades\Validator::make($data, $rules);
        
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    /**
     * Assign default role based on user type.
     */
    private function assignDefaultRole(User $user): void
    {
        // This will be implemented when roles and permissions are set up
        // For now, we'll just log the assignment
        Log::info('Default role assignment needed', [
            'user_id' => $user->id,
            'type' => $user->type
        ]);
    }
} 