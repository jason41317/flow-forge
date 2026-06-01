<?php

namespace App\Actions;

use App\Models\AuditLog;

class CreateAuditLogAction
{
    public static function run(
        int $tenantId,
        ?int $userId,
        string $event,
        string $entityType,
        int $entityId,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'tenant_id' => $tenantId,
            'user_id' => $userId,
            'event' => $event,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
