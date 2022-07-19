<?php
namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

trait CanUploadImage
{
    use CanLog;

    /**
     * @var $allowedMimeTypes
    */
    public $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/bmp', 'image/gif', 'image/webp', 'application/octet-stream'
    ];

    /**
     * @var $stagedFilePath
    */
    public $stagedFilePath;

    /**
     * @var $stagedFile
    */
    protected $stagedFile;

    /**
     * @var $allowedImageMimeTypes
    */
    public $allowedImageMimeTypes = [
        'image/png'
    ];

    /**
     * @var $sizesToCreate
    */
    private $sizesToCreate;

    /**
     * Upload file
     *
     * @param string $staged_file_path
     * @param Model $modelToUpdateAfterUpload
     * @param array $sizesToCreate
     *
     * @throws Exception
     */
    private static function uploadImage(string $staged_file_path, Model $modelToUpdateAfterUpload, array $sizesToCreate)
    {
        if(empty($staged_file_path)){
            throw new Exception('Missing staged file path');
        }

        if(!file_exists($staged_file_path)){
            if (filter_var($staged_file_path, FILTER_VALIDATE_URL)) {
                $staged_file_path = self::_downloadFile($staged_file_path, 'staged-files')->data['staged_file_path'];
            }
        }

        if(!file_exists($staged_file_path)){
            throw new Exception('Missing staged file path');
        }

        if(is_null($modelToUpdateAfterUpload)){
            throw new Exception('Missing model to update on image upload.');
        }

        if(!$modelToUpdateAfterUpload instanceof Model){
            throw new Exception('Invalid model to update on image upload.');
        }

        if(!is_array($sizesToCreate) || count($sizesToCreate) <= 0){
            throw new Exception('Invalid sizes list.');
        }

        foreach ($sizesToCreate as $size){
            $size = (object)$size;
            $column = isset($size->column) && !empty($size->column) ? trim($size->column) : null;
            $width = isset($size->width) && !empty($size->width) ? trim($size->width) : null;
            $height = isset($size->height) && !empty($size->height) ? trim($size->height) : null;

            if(!empty($column) && !empty($width) && !empty($height)){
                $result = self::_makeNewImage($staged_file_path, $width, $height);

                if(isset($result->file_path_cdn) && !empty($result->file_path_cdn)){
                    $modelToUpdateAfterUpload->update([
                        ''.$column.'' => $result->file_path_cdn
                    ]);
                }
            }
        }

        @unlink($staged_file_path);
    }

    /**
     * Create file size
     * @param $sourceImagePath
     * @param $width
     * @param $height
     *
     * @return object|null
     */
    private static function _makeNewImage($sourceImagePath, $width, $height): ?object
    {
        try{
            if(file_exists($sourceImagePath)){

                if(!empty($width) && !empty($height)){
                    $fileInfo = new \SplFileInfo($sourceImagePath);

                    if(!is_null($fileInfo)){
                        $filePath = $fileInfo->getPath();
                        $fileExtension = $fileInfo->getExtension();

                        if(!empty($filePath) && !empty($fileExtension)){
                            $fileName = Str::random(20).'.'.$fileExtension;
                            $tempPath = $filePath.DIRECTORY_SEPARATOR.$fileName;

                            Image::load($sourceImagePath)
                                ->optimize()
                                ->orientation(Manipulations::ORIENTATION_AUTO)
                                ->fit(Manipulations::FIT_CONTAIN, $width, $height)
                                ->contrast(config('craydle.images.image_contrast'))
                                ->brightness(config('craydle.images.images_brightness'))
                                ->save($tempPath);

                            if(file_exists($tempPath)){
                                $result = self::_toCDN($tempPath, $fileName);

                                if(isset($result->status) && $result->status == true){
                                    if(!empty($tempPath)){
                                        unlink($tempPath);
                                    }

                                    return (object)array(
                                        'filename' => $fileName,
                                        'file_path_cdn' => isset($result->file_path_cdn) ? $result->file_path_cdn : ""
                                    );
                                }
                            }
                        }
                    }
                }
            }

            return null;
        }catch (Exception $exception){
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Upload image
     *
     * @param string $filePath
     * @param string $fileName
     *
     * @return object
     */
    private static function _toCDN(string $filePath, string $fileName): object
    {
        try{
            if(!file_exists($filePath)){
                throw new Exception('Could not locate the file to move to the CDN');
            }

            $spaceAccessKey  = config( 'craydle.files_storage.storage_key' );
            $spaceSecretKey  = config( 'craydle.files_storage.storage_secret' );
            $spaceBucketName = config( 'craydle.files_storage.storage_bucket_name' );
            $spaceRegion     = config( 'craydle.files_storage.storage_server_region' );

            if(empty($spaceAccessKey)){
                throw new Exception('Missing CDN spaces API key');
            }

            if(empty($spaceSecretKey)){
                throw new Exception('Missing CDN spaces API Secret');
            }

            if(empty($spaceBucketName)){
                throw new Exception('Missing CDN spaces bucket name');
            }

            if(empty($spaceRegion)){
                throw new Exception('Missing CDN spaces region name');
            }

            $client = new S3Client([
                'endpoint' => 'https://fra1.digitaloceanspaces.com',
                'credentials' => [
                    'key'    => $spaceAccessKey,
                    'secret' => $spaceSecretKey
                ],
                'region' => $spaceRegion,
                'version' => 'latest',
                'visibility' => 'public',
            ]);

            $adapter = new AwsS3Adapter($client, $spaceBucketName);
            $filesystem = new Filesystem($adapter);

            if(!$filesystem->has($fileName)){
                $filesystem->write($fileName, file_get_contents($filePath), [
                    'visibility' => AdapterInterface::VISIBILITY_PUBLIC
                ]);

                return (object)array(
                    'status' => true,
                    'filename' => $fileName,
                    'file_path_cdn' => sprintf(
                        config('craydle.files_storage.storage_server_file_cdn_path'),
                        $fileName
                    ),
                    'msg' => 'File uploaded'
                );
            }else{
                return (object)array(
                    'status' => false,
                    'msg' => 'File already exists.'
                );
            }
        }catch (Exception $exception){
            (new self())->logException($exception);

            return (object)array(
                'status' => false,
                'msg' => $exception->getMessage()
            );
        } catch (FilesystemException $e) {
            (new self())->logException($e);

            return (object)array(
                'status' => false,
                'msg' => $e->getMessage()
            );
        }
    }

    /**
     * Delete uploaded image
     *
     * @param string $filePath
     *
     * @return void
     */
    private static function _deleteFromCDN(string $filePath){
        try{
            $file_name = CraydelHelperFunctions::getFileNameFromURL($filePath);

            if(empty($file_name)){
                throw new Exception('Invalid CDN image path. Can not retrieve the file name.');
            }

            Log::info('File to delete: '.$file_name);

            $spaceAccessKey  = config( 'craydle.files_storage.storage_key' );
            $spaceSecretKey  = config( 'craydle.files_storage.storage_secret' );
            $spaceBucketName = config( 'craydle.files_storage.storage_bucket_name' );
            $spaceRegion     = config( 'craydle.files_storage.storage_server_region' );

            if(empty($spaceAccessKey)){
                throw new Exception('Missing CDN spaces API key');
            }

            if(empty($spaceSecretKey)){
                throw new Exception('Missing CDN spaces API Secret');
            }

            if(empty($spaceBucketName)){
                throw new Exception('Missing CDN spaces bucket name');
            }

            if(empty($spaceRegion)){
                throw new Exception('Missing CDN spaces region name');
            }

            $client = new S3Client([
                'endpoint' => 'https://fra1.digitaloceanspaces.com',
                'credentials' => [
                    'key'    => $spaceAccessKey,
                    'secret' => $spaceSecretKey
                ],
                'region' => $spaceRegion,
                'version' => 'latest',
                'visibility' => 'public',
            ]);

            $adapter = new AwsS3Adapter($client, $spaceBucketName);
            $filesystem = new Filesystem($adapter);

            if($filesystem->has($file_name)){
                $filesystem->delete($file_name);
            }
        }catch (\Exception $exception){
            (new self())->logException($exception);
        }
    }

    /**
     * Down file from path provided
     *
     * @param string $url
     * @param string $destination
     *
     * @return CraydelInternalResponseHelper
    */
    private static function _downloadFile(string $url, string $destination): CraydelInternalResponseHelper
    {
        try{
            if(empty($url)){
                throw new Exception('Missing image download path.');
            }

            if(empty($destination)){
                throw new Exception('Missing image destination path.');
            }

            $_destination = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.$destination;

            if(!file_exists($_destination)){
                Storage::makeDirectory($destination);
            }

            $file = parse_url($url);

            if(is_null($file)){
                throw new Exception('Invalid image URL provided.');
            }

            $file_name = substr($file['path'], strrpos($file['path'], '/') + 1);

            if(empty($file_name)){
                throw new Exception('Unable to get the file name.');
            }

            $file_extension = substr($file_name, strrpos($file_name, '.') + 1);

            if(empty($file_extension)){
                throw new Exception('Unable to get the file extension.');
            }

            if(!file_exists($_destination)){
                throw new Exception('Invalid destination path.');
            }

            $stage_file_name = CraydelHelperFunctions::makeRandomString(10, null, true).'.'.$file_extension;
            $_destination = $_destination.DIRECTORY_SEPARATOR.$stage_file_name;

            $fp = fopen($_destination, 'w');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);

            curl_exec($ch);

            if(curl_errno($ch)){
                throw new \Exception(curl_error($ch));
            }

            curl_close($ch);
            fclose($fp);

            return (new CraydelInternalResponseHelper(
                true,
                'Downloaded',[
                    'staged_file_path' => $_destination
                ]
            ));
        }catch (\Exception $exception){
            return (new CraydelInternalResponseHelper(
                false,
                $exception->getMessage(),
                null,
                $exception
            ));
        }
    }
}
