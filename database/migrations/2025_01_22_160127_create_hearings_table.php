<?php

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
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->date('requested_at');
            $table->foreignId('beneficiary_id')->constrained();
            $table->foreignId('request_type_id')->constrained();
            $table->text('details')->nullable()->default(null);
            $table->dateTime('hearing_date')->nullable()->default(null);
            $table->dateTime('hearing_time')->nullable()->default(null);
            $table->boolean('did_assist')->nullable()->default(null);
            $table->text('notes')->nullable()->default(null);
            $table->string('attachment')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearings');
    }
};
