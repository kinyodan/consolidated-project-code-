<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('institutions')){
            Schema::table('institutions', function (Blueprint $table) {
                if (!Schema::hasColumn('institutions', 'city')) {
                    $table->string('city')->after('profile_details')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'email_address')) {
                    $table->string('email_address')->after('city')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'academic_office_phone_number')) {
                    $table->string('academic_office_phone_number')->after('email_address')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'academic_office_email_address')) {
                    $table->string('academic_office_email_address')->after('academic_office_phone_number')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'academic_office_postal_address')) {
                    $table->text('academic_office_postal_address')->after('academic_office_email_address')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'university_postal_address')) {
                    $table->text('university_postal_address')->after('academic_office_postal_address')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'seo_keywords')) {
                    $table->text('seo_keywords')->after('university_postal_address')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'seo_description')) {
                    $table->text('seo_description')->after('seo_keywords')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'system_internal_ranking')) {
                    $table->integer('system_internal_ranking')->after('seo_description')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'phone_number')) {
                    $table->string('phone_number')->after('system_internal_ranking')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'country_ranking')) {
                    $table->string('country_ranking')->after('phone_number')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'regional_ranking')) {
                    $table->string('regional_ranking')->after('country_ranking')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'continental_ranking')) {
                    $table->string('continental_ranking')->after('regional_ranking')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'global_ranking')) {
                    $table->string('global_ranking')->after('continental_ranking')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'date_registered')) {
                    $table->string('date_registered')->after('global_ranking')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'accredited_by_acronym')) {
                    $table->string('accredited_by_acronym')->after('date_registered')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'accredited_by')) {
                    $table->text('accredited_by')->after('accredited_by_acronym')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'website_url')) {
                    $table->text('website_url')->after('accredited_by')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'logo_url')) {
                    $table->text('logo_url')->after('website_url')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'inquiry_form_url')) {
                    $table->text('inquiry_form_url')->after('logo_url')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'finance_office_phone_number')) {
                    $table->text('finance_office_phone_number')->after('inquiry_form_url')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'finance_office_email_address')) {
                    $table->text('finance_office_email_address')->after('finance_office_phone_number')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'main_campus_physical_location')) {
                    $table->text('main_campus_physical_location')->after('finance_office_email_address')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'main_campus_latitude')) {
                    $table->text('main_campus_latitude')->after('main_campus_physical_location')->nullable();
                }

                if (!Schema::hasColumn('institutions', 'main_campus_longtitude')) {
                    $table->text('main_campus_longtitude')->after('main_campus_latitude')->nullable();
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
        if(Schema::hasTable('institutions')) {
            Schema::table('institutions', function (Blueprint $table) {
                $table->dropColumn('city');
                $table->dropColumn('email_address');
                $table->dropColumn('academic_office_phone_number');
                $table->dropColumn('academic_office_email_address');
                $table->dropColumn('academic_office_postal_address');
                $table->dropColumn('university_postal_address');
                $table->dropColumn('seo_keywords');
                $table->dropColumn('seo_description');
                $table->dropColumn('system_internal_ranking');
                $table->dropColumn('phone_number');
                $table->dropColumn('country_ranking');
                $table->dropColumn('regional_ranking');
                $table->dropColumn('continental_ranking');
                $table->dropColumn('global_ranking');
                $table->dropColumn('date_registered');
                $table->dropColumn('accredited_by_acronym');
                $table->dropColumn('accredited_by');
                $table->dropColumn('website_url');
                $table->dropColumn('logo_url');
                $table->dropColumn('inquiry_form_url');
                $table->dropColumn('finance_office_phone_number');
                $table->dropColumn('finance_office_email_address');
                $table->dropColumn('main_campus_physical_location');
                $table->dropColumn('main_campus_latitude');
                $table->dropColumn('main_campus_longtitude');
            });
        }
    }
}
