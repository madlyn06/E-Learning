<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('cms__keywordables', function (Blueprint $table) {
            // Columns
            $table->integer('keyword_id')->unsigned();
            $table->morphs('keywordable');
            $table->timestamps();

            // Indexes
            $table->unique(['keyword_id', 'keywordable_id', 'keywordable_type'], 'keywordables_ids_type_unique');
            $table->foreign('keyword_id')->references('id')->on('cms__keywords')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cms__keywordables');
    }
};
