<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertNestedToCmsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms__pages', function (Blueprint $table) {
            $table->nestedSet();
        });

        \Newnet\Cms\Models\Page::fixTree();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms__pages', function (Blueprint $table) {
            $table->dropNestedSet();
        });
    }
}
