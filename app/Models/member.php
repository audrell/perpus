<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    protected $guarded = [];

    public static function generateNextMemberCode(): string
    {
        $lastCode = self::lockForUpdate()->orderByDesc('id')->value('member_code');

        $lastNumber = 0;
        if (!empty($lastCode) && preg_match('/^MBR-(\d+)$/', $lastCode, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        return 'MBR-' . str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relasi Many to One dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
