<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('status')->default('new')->change();
            $table->string('category')->default('other')->after('description');
        });

        DB::table('tickets')->where('status', 'open')->update(['status' => 'new']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('tickets')->where('status', 'new')->update(['status' => 'open']);
        DB::table('tickets')->whereIn('status', ['waiting', 'resolved'])->update(['status' => 'in_progress']);

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('status')->default('open')->change();
            $table->dropColumn('category');
        });
    }
};
