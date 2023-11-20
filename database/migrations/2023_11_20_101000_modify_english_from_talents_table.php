<?php

use App\Enums\EnglishLevel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            //
            $table->enum("english", array_map(fn($level) => $level->value, EnglishLevel::cases()))->nullable()->after('phone')->comment('
            case NO_ENGLISH = 0;
            case BASIC = 5;
            case INTERMEDIATE = 10;
            case ADVANCED = 15;
            case FLUENTLY = 20;
            ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talents', function (Blueprint $table) {
            $table->dropColumn('english');
        });
    }
};
