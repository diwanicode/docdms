<?php

namespace App\Domain\Business;

use App\Models\Business;
use App\Models\BusinessEmployee; 
use App\Models\User;
use App\Repositories\Business\BusinessRepository;
use App\Repositories\Business\BusinessEmployeeRepository;
use App\Services\Business\BusinessPermissionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BusinessChecker
{
    /**
     * Create a new class instance.
     */
    public function __construct( public BusinessEmployeeRepository $businessEmployeeRepository,
                                  public BusinessRepository $businessRepository, 
                                   public BusinessPermissionService $businessPermissionService,)
    {
        //
    }
    public function getBusinessBySlug(string $slug)
    {
        return $this->businessRepository->findBusinessBySlug($slug);
    }
    public function employeeHasBusinessAccess(Business $business, User $user): bool
    {
        $isOwner= $this->businessEmployeeRepository->isOwner($business,$user);
        $isEmployee= $this->businessEmployeeRepository->isEmployee($business,$user);
        $isSystemAdmin= $this->businessEmployeeRepository->isSystemAdmin($user);
         Log::info('$isOwner ' .$isOwner);
           Log::info('$isEmployee ' .$isEmployee);
             Log::info('$isSystemAdmin ' .$isSystemAdmin);
        if($isOwner || $isEmployee){
            return true;
        }
        return false;
    }
    public function employeeAllPermissions(User $user): array 
    {
        return $this->businessPermissionService->getAllForUserPermissions($user);
    }
    public function employeeHasPermission(BusinessEmployee $employee, string $permissionSlug): bool
    {
        return $employee->departments()
            ->whereHas('permissions', function ($q) use ($permissionSlug) {
                $q->where('slug', $permissionSlug);
            })
            ->exists();
    }
    public function redirectUserToBusinessDashboard($user)
    {
         if (!($user instanceof User)) {
            // If $user has 'email' property or key (like $request->email)
            $email = is_object($user) ? ($user->email ?? null) : (is_array($user) ? ($user['email'] ?? null) : null);
            if ($email) {
                $user = User::where('email', $email)->first();
            }
        }

        // If still no user found
        if (!$user) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'User not found.');
        }

        $businessEmployee = $user->businessEmployees()->first(); 
        if (!$businessEmployee) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'You are not in the system.');
        }

        $business = $businessEmployee->business;       
        if (!$business) {
            Auth::logout();
            return redirect()->route('login')->with('message', 'Business not found.');
        }
        return redirect()->route('business.show', ['business' => $business->slug]);
    }
    
}
