<?php

use App\Models\School;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SchoolBankDetail;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(): void
  {
    
    if (Schema::hasTable('school_bank_details')) {
      Schema::table('school_bank_details', function (Blueprint $table) {
        if (!Schema::hasColumn('school_bank_details', 'account_name')) {
          $table->string('account_name')->nullable()->after('id');
        }
        if (!Schema::hasColumn('school_bank_details', 'account_number')) {
          $table->string('account_number')->nullable()->after('account_name');
        }
        if (!Schema::hasColumn('school_bank_details', 'bank_name')) {
          $table->string('bank_name')->nullable()->after('account_number');
        }
        if (!Schema::hasColumn('school_bank_details', 'branch_name')) {
          $table->string('branch_name')->nullable()->after('bank_name');
        }
        if (!Schema::hasColumn('school_bank_details', 'swift_code')) {
          $table->string('swift_code')->nullable()->after('branch_name');
        }
        if (!Schema::hasColumn('school_bank_details', 'school_id')) {
          $table->unsignedBigInteger('school_id')->after('branch_name')->default(1);
          $table->foreign('school_id', 'FK_schools_school_id')
            ->references('id')
            ->on((new SchoolBankDetail())->getTable())
            ->onDelete('cascade');
        }
        if (!Schema::hasColumn('school_bank_details', 'status')) {
          $table->bigInteger('status')->default(1)->after('school_id');
        }
        if (!Schema::hasColumn('school_bank_details', 'is_deleted')) {
          $table->tinyInteger('is_deleted')->default(0)->after('status');
        }
        if (!Schema::hasColumn('school_bank_details', 'deleted_at')) {
          $table->string('deleted_at')->nullable()->after('is_deleted');
        }
        if (!Schema::hasColumn('school_bank_details', 'created_by')) {
          $table->string('created_by')->nullable()->after('deleted_at');
        }
        if (!Schema::hasColumn('school_bank_details', 'updated_by')) {
          $table->string('updated_by')->nullable()->after('created_by');
        }
        if (!Schema::hasColumn('school_bank_details', 'deleted_by')) {
          $table->string('deleted_by')->nullable()->after('updated_by');
        }
      });
    }
  }
  
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(): void
  {
    Schema::table('school_bank_details', function (Blueprint $table) {
      if (Schema::hasColumn('school_bank_details', 'account_name')) {
        $table->dropColumn('account_name');
      }
      if (Schema::hasColumn('school_bank_details', 'account_number')) {
        $table->dropColumn('account_number');
      }
      if (Schema::hasColumn('school_bank_details', 'branch_name')) {
        $table->dropColumn('branch_name');
      }
      if (Schema::hasColumn('school_bank_details', 'swift_code')) {
        $table->dropColumn('swift_code');
      }
      Schema::disableForeignKeyConstraints();
      if (Schema::hasColumn('school_bank_details', 'school_id')) {
        $table->dropColumn('school_id');
      }
      Schema::enableForeignKeyConstraints();
      if (Schema::hasColumn('school_bank_details', 'status')) {
        $table->dropColumn('status');
      }
      if (Schema::hasColumn('school_bank_details', 'is_deleted')) {
        $table->dropColumn('is_deleted');
      }
      if (Schema::hasColumn('school_bank_details', 'deleted_at')) {
        $table->dropColumn('deleted_at');
      }
      
      if (Schema::hasColumn('school_bank_details', 'created_by')) {
        $table->dropColumn('created_by');
      }
      
      if (Schema::hasColumn('school_bank_details', 'updated_by')) {
        $table->dropColumn('updated_by');
      }
      
      if (Schema::hasColumn('school_bank_details', 'deleted_by')) {
        $table->dropColumn('deleted_by');
      }
    });
  }
};
  
