<?php
namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

trait CanUploadExcel
{
    use CanLog;

    /**
     * @var $allowedMimeTypes
    */
    public $allowedExcelMimeTypes = [
        'application/xml','application/vnd.ms-excel','application/msexcel','application/x-msexcel','application/x-ms-excel',
        'application/x-excel','application/x-dos_ms_excel','application/xls','application/x-xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    /**
     * @var $allowedFileExtensions
     */
    public $allowedFileExtensions = [
        'xls','xlsx'
    ];


}
