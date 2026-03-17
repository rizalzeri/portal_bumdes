<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumFeature extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    /**
     * Helper check if feature is permitted for free user or current user
     */
    public static function isAllowed($bumdes, $key)
    {
        $feature = self::where('key', $key)->first();
        if (!$feature) return true; // Tdk diatur = gratis
        
        // Klo fitur gak di lock premium, bebas.
        if (!$feature->is_premium) return true;

        // Cek langganan bumdes aktif?
        // Cari history langganan (kalau model Langganan/Subscription ada)
        // Kita perlu tau model Langganan bentuknya gmna.
        $hasPremium = false;
        if($bumdes->langganan()->where('status', 'aktif')->exists()){
            $hasPremium = true;
        }

        if ($hasPremium) return true;

        if ($feature->free_limit !== null) {
            return 'limit';
        }

        return false;
    }
}
