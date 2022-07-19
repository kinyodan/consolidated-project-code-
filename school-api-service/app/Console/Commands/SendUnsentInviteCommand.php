<?php
namespace App\Console\Commands;

use App\Http\Controllers\StudentsController;
use App\Http\Controllers\Traits\CanLog;
use App\Models\Student;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class SendUnsentInviteCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulk:get-unsent-invites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command get unsent invites';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return void
     */
    public function handle() {
        try{
            Student::where('is_invite_sent', 0)
                ->chunk(10, function ($students){
                    foreach ($students as $student){
                        if(DB::table((new Student())->getTable())->where('id', $student->id)->update(['is_invite_sent' => 1])){
                            (new StudentsController())->triggerEmail($student);
                        }
                    }
                });
        }catch (Exception $exception){
            self::logException($exception);
        } catch (Throwable $e) {
            self::logException($e);
        }
    }
}
