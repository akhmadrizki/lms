<?php

use App\ShowStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('slug')->index();
            $table->string('name');
            $table->foreignId('coaching_firm_id')->nullable()->constrained('coaching_firms')->nullOnDelete();
            $table->enum('show_to', ShowStatus::values())->nullable();
            $table->text('description')->nullable();
            $table->integer('duration')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
