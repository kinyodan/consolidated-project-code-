<?php
namespace Feature;

use App\Http\Controllers\Helpers\AcademicDisciplineHelper;
use Illuminate\Support\Facades\Schema;

class LearningModeTest extends \TestCase
{
    use \TestingHelperFunction;

    /**
     * Test that course types has data
    */
    public function testIfLearningModeHelperModesFunctionReturnsAnObjectArray(){
        $this->withoutExceptionHandling();
        Schema::disableForeignKeyConstraints();
        $this->artisan('db:seed');
        Schema::enableForeignKeyConstraints();

        $academic_disciplines = AcademicDisciplineHelper::disciplines();
        $this->assertIsArray($academic_disciplines);
        $this->assertTrue(count($academic_disciplines) > 0);
        $this->assertIsObject($academic_disciplines[0]);
        $this->assertObjectHasAttribute('id', $academic_disciplines[0]);
        $this->assertObjectHasAttribute('discipline_code', $academic_disciplines[0]);
        $this->assertObjectHasAttribute('discipline_name', $academic_disciplines[0]);
    }
}
