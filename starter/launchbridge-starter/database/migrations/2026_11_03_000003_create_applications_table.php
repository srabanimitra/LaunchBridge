<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('graduate_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('status', 20)->default('pending');
            $table->timestamps();
            $table->unique(['project_id', 'graduate_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('applications'); }
};
