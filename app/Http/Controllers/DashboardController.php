<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard overview.
     */
    public function index()
    {
        $siswasCount = \App\Models\Siswa::countSiswa();
        $stats = [
            'total_revenue' => 12500000,
            'total_orders' => 258,
            'new_customers' => 42,
            'conversion_rate' => 24.8
        ];

        return view('dashboard.main.index', compact('stats', 'siswasCount'));
    }

    /**
     * Display the settings page.
     */
    public function settings()
    {
        return view('dashboard.settings.index');
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'current_password' => 'nullable|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Update profile
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('new_password')) {
            $user->password = bcrypt($request->new_password);
        }

        $user->save();

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
