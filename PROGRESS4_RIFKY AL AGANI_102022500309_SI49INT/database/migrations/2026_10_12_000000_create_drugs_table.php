<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->string('drug_code', 50)->unique();
            $table->string('drug_name', 150);
            $table->string('dosage', 50);
            $table->string('category', 100);
            $table->string('preparation_form', 30);
            $table->string('manufacturer', 150);
            $table->string('bpom_number', 50)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('unit', 30);
            $table->decimal('price', 12, 2)->default(0);
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
