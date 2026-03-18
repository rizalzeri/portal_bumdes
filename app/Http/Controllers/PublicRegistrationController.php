<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Kabupaten;
use App\Models\Bumdes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PublicRegistrationController extends Controller
{
    public function create()
    {
        // Fix duplicate provinces by name
        $provinces = Province::orderBy('name')->get()->unique('name');
        $pricingConfigs = \App\Models\PricingConfig::active()->get();
        return view('public.register', compact('provinces', 'pricingConfigs'));
    }

    public function getKabupatens(Province $province)
    {
        // Fix relationship name from kabupaten() to kabupatens()
        return response()->json($province->kabupatens()->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:provinces,id',
            'kabupaten_id' => 'required|exists:kabupatens,id',
            'desa' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username|regex:/^[a-zA-Z0-9\-]+$/',
            'password' => 'required|string|min:6|confirmed',
            'package' => 'required|in:gratis,premium',
            'pricing_config_id' => 'required_if:package,premium|exists:pricing_configs,id',
        ], [
            'username.regex' => 'Username/Domain hanya boleh berisi huruf, angka, dan strip (-).',
            'username.unique' => 'Domain/Username ini sudah digunakan, silakan pilih yang lain.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'pricing_config_id.required_if' => 'Silakan pilih paket premium yang Anda inginkan.',
        ]);

        DB::beginTransaction();

        try {
            $statusBumdes = $request->package === 'premium' ? true : false;
            // Set as pending_premium so user can login but does not bypass premium checks (unless paid)
            $subscriptionStatus = $request->package === 'premium' ? 'pending_premium' : 'inactive';

            // Create BUMDes
            $bumdes = Bumdes::create([
                'kabupaten_id' => $request->kabupaten_id,
                'name' => 'BUMDes ' . $request->desa,
                'slug' => $request->username,
                'desa' => $request->desa,
                'is_active' => $statusBumdes,
            ]);

            // Create User
            $user = User::create([
                'name' => 'Admin BUMDes ' . $request->desa,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'bumdes_id' => $bumdes->id,
                'kabupaten_id' => $request->kabupaten_id,
                'subscription_status' => $subscriptionStatus,
                'subscription_package' => $request->package,
            ]);

            // Set user_id back to bumdes record
            $bumdes->update(['user_id' => $user->id]);

            if ($request->package === 'premium') {
                $plan = \App\Models\PricingConfig::findOrFail($request->pricing_config_id);
                $totalAmount = $plan->total_price;
                $orderId = 'BUMDES-' . $bumdes->id . '-REG-' . time();
                
                // Create pending Langganan
                \App\Models\Langganan::create([
                    'bumdes_id'       => $bumdes->id,
                    'package_name'    => $plan->name . ' (' . $plan->months . ' Bulan)',
                    'order_id'        => $orderId,
                    'amount'          => $totalAmount,
                    'duration_months' => $plan->months,
                    'status'          => 'pending',
                    'start_date'      => now(),
                    'end_date'        => now()->addMonths($plan->months),
                ]);
            }

            DB::commit();

            if ($request->package === 'premium') {
                \Illuminate\Support\Facades\Auth::login($user);
                return redirect()->route('user.langganan.index', $user->username)
                    ->with('open_payment', true)
                    ->with('success', 'Pendaftaran berhasil! Akun Anda aktif. Silakan selesaikan pembayaran untuk mengaktifkan fitur Premium.');
            } else {
                return redirect()->route('login')->with('success', 'Pendaftaran berhasil!')->with('info', 'Akun Anda sedang menunggu konfirmasi dari Admin Kabupaten sebelum bisa digunakan untuk login.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
    }
}
