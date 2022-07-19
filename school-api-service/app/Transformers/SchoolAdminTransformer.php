<?php
namespace App\Transformers;

use App\Models\SchoolAdmin;
use League\Fractal\TransformerAbstract;

class SchoolAdminTransformer extends TransformerAbstract
{
    public function transform(SchoolAdmin $schoolAdmin): array
    {
        return [
            'id' => isset($schoolAdmin->id) ? (int)$schoolAdmin->id : null,
            'admin_name' => $schoolAdmin->admin_name ?? null,
            'admin_email' => $schoolAdmin->admin_email ?? null,
            'admin_phone' => $schoolAdmin->admin_phone ?? null,
            'craydel_user_id' => $schoolAdmin->admin_address ?? null,
            'admin_address' => $schoolAdmin->admin_address ?? null,
            'is_craydel_account_created' => $schoolAdmin->is_craydel_account_created ?? null,
            'is_invite_sent' => $schoolAdmin->is_invite_sent ?? null,
            'is_account_activated' => $schoolAdmin->is_account_activated ?? null,
            'created_at' => isset($schoolAdmin->created_at) ? $schoolAdmin->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => isset($schoolAdmin->updated_at) ? $schoolAdmin->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
