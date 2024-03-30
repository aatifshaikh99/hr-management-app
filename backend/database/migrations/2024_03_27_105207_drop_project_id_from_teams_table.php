<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropForeign(['project_id']); // Drop foreign key constraint
            $table->dropColumn('project_id'); // Drop the column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            // If you ever need to revert this migration, you can add back the column here
            $table->foreignId('project_id')->constrained('projects');
        });
    }
};
