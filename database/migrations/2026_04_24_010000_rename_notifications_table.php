<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'notifiable_type')) {
            Schema::rename('notifications', 'custom_notifications');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('custom_notifications')) {
            Schema::rename('custom_notifications', 'notifications');
        }
    }
};
