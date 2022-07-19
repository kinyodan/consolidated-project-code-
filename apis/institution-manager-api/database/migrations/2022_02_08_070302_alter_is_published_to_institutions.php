<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterIsPublishedToInstitutions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if (!Schema::hasColumn('institutions', 'is_published')) {
                    $table->tinyInteger('is_published')
                        ->after('is_featured')
                        ->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                if (Schema::hasColumn('institutions', 'is_published')) {
                    Schema::disableForeignKeyConstraints();
                    DB::statement("alter table institutions modify column is_published decimal(8, 2) not null;");
                    Schema::enableForeignKeyConstraints();
                }
            });
        }
    }
}
