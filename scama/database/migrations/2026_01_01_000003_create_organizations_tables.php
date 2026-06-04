<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->string('logo_url', 500)->nullable();
            $table->string('website', 500)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->unique('slug', 'unique_organizations_slug');
            $table->index('status', 'idx_organizations_status');
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
$table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();

            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique('slug', 'unique_permissions_slug');
            $table->index('organization_id', 'idx_permissions_organization');
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();

            $table->primary(['role_id', 'permission_id']);
            $table->index('permission_id', 'idx_role_permissions_permission');
        });

        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->timestamp('assigned_at')->useCurrent();

            $table->primary(['user_id', 'role_id', 'organization_id']);
            $table->index('role_id', 'idx_user_roles_role');
            $table->index('organization_id', 'idx_user_roles_organization');
            $table->index(['user_id', 'organization_id'], 'idx_user_roles_user_org');
        });

        Schema::create('organization_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 20)->default('member');
            $table->timestamp('joined_at')->useCurrent();

            $table->unique(['organization_id', 'user_id'], 'unique_org_members_org_user');
            $table->index('user_id', 'idx_org_members_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_members');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('organizations');
    }
};
