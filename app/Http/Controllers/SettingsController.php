<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class SettingsController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()->orderBy('name')->get();
        $whatsapp = WhatsAppSetting::current();

        return view('settings.index', compact('users', 'whatsapp'));
    }

    public function updateWhatsApp(Request $request)
    {
        $validated = $request->validate([
            'business_number' => ['nullable', 'string', 'max:30'],
            'default_country_code' => ['required', 'string', 'max:8'],
            'enabled' => ['nullable', 'boolean'],
            'solar_follow_up_template' => ['required', 'string', 'max:5000'],
            'sofa_cleaning_reminder_template' => ['required', 'string', 'max:5000'],
            'deep_cleaning_reminder_template' => ['required', 'string', 'max:5000'],
            'payment_reminder_template' => ['required', 'string', 'max:5000'],
            'thank_you_template' => ['required', 'string', 'max:5000'],
        ]);

        $validated['enabled'] = $request->boolean('enabled');

        WhatsAppSetting::current()->update($validated);

        return back()->with('success', 'WhatsApp integration settings updated successfully.');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:admin,staff'],
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('settings.index')->with('success', 'User created successfully.');
    }

    public function toggleUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($validated['password'])]);
        return back()->with('success', 'Password updated successfully.');
    }
}
