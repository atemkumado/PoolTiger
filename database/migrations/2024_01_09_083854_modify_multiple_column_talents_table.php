<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->string('firstname')->nullable()->after('id');
            $table->string('lastname')->nullable()->after('firstname');
            $table->string('lead_no')->nullable()->after('lastname');
            $table->string('phone')->nullable()->change();
//            $table->dropUnique(['phone']);
            $table->string('email')->nullable()->change();
            $table->string('experience')->change();
            $table->dropColumn("portfolio");
        });
        DB::statement("ALTER TABLE talents MODIFY english VARCHAR(10)");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropColumn("firstname");
            $table->dropColumn("lastname");
            $table->dropColumn("lead_no");
            $table->string('email')->change();
            $table->string('experience')->change();
            $table->string("portfolio");
        });
        DB::statement("ALTER TABLE talents MODIFY english ENUM('','0', '5', '10','15','20')");

    }
};

