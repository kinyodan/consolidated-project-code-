<?php
namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Helpers\CraydelHelperFunctions;
use App\Http\Controllers\Helpers\CraydelInternalResponseHelper;
use Aws\S3\S3Client;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3V3\AwsS3Adapter;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

trait CanUploadImage
{
  /**
   * @var array $allowedImageMimeTypes
   */
  public array $allowedImageMimeTypes = [
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/bmp',
    'image/gif',
    'image/webp',
    'application/octet-stream'
  ];
  
  /**
   * @var array|null $sizesToCreate
   */
  private ?array $sizesToCreate;
  
  /**
   * Upload file
   *
   * @param string $staged_file_path
   * @param Model $modelToUpdateAfterUpload
   * @param array $sizesToCreate
   * @param string|null $model_temp_file_column
   *
   */
  private static function uploadImage(string $staged_file_path, Model $modelToUpdateAfterUpload, array $sizesToCreate, ?string $model_temp_file_column = 'temp_logo_path'): void
  {
    try{
      if(empty($staged_file_path)){
        throw new Exception('Missing stage file path');
      }
      
      if(!file_exists($staged_file_path)){
        if (filter_var($staged_file_path, FILTER_VALIDATE_URL)) {
          $staged_file_path = self::_downloadFile($staged_file_path, 'staged-files')->data['staged_file_path'];
        }
      }
      
      if(!file_exists($staged_file_path)){
        throw new Exception('Missing staged file path');
      }
      
      if(count($sizesToCreate) <= 0){
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
      
      if(!empty($model_temp_file_column)){
        if (Schema::hasColumn($modelToUpdateAfterUpload->getTable(), $model_temp_file_column)) {
          $modelToUpdateAfterUpload->update([
            ''.$model_temp_file_column.'' => null
          ]);
        }
      }
      
      @unlink($staged_file_path);
    }catch (Exception $exception){
      (new self())->logException($exception);
      
      $modelToUpdateAfterUpload->update([
        'logo_cdn_upload_error' => $exception->getMessage()
      ]);
    }
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
                ->contrast(config('craydle.logos.image_contrast'))
                ->brightness(config('craydle.logos.images_brightness'))
                ->save($tempPath);
              
              if(file_exists($tempPath)){
                $result = self::_toCDN($tempPath, $fileName);
                
                if(isset($result->status) && $result->status){
                  if(!empty($tempPath)){
                    unlink($tempPath);
                  }
                  
                  return (object)array(
                    'filename' => $fileName,
                    'file_path_cdn' => $result->file_path_cdn ?? ""
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
      
      
      $spaceAccessKey  = config( 'services.files_storage.storage_key' );
      $spaceSecretKey  = config( 'services.files_storage.storage_secret' );
      $spaceBucketName = config( 'services.files_storage.storage_bucket_name' );
      $spaceRegion     = config( 'services.files_storage.storage_server_region' );
      
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
      
      
      
      $adapter = new AwsS3V3Adapter($client, $spaceBucketName);
      $filesystem = new Filesystem($adapter);
      
      if(!$filesystem->has($fileName)){
        $filesystem->write($fileName, file_get_contents($filePath), [
          'visibility' => 'public'
        ]);
        
        @unlink($filePath);
        
        return (object)array(
          'status' => true,
          'filename' => $fileName,
          'file_path_cdn' => sprintf(
            config('services.files_storage.storage_server_file_cdn_path'),
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
      Log::error($exception->getMessage());
      
      return (object)array(
        'status' => false,
        'msg' => $exception->getMessage()
      );
    } catch (FilesystemException $e) {
      Log::error($e->getMessage());
      
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
  public static function _deleteFromCDN(string $filePath): void
  {
    try{
      if(CraydelHelperFunctions::isNull($filePath) || CraydelHelperFunctions::isURL($filePath)){
        throw new Exception('Missing or invalid file path while deleting the image from CDN.');
      }
      
      $file_name = CraydelHelperFunctions::getFileNameFromURL($filePath);
      
      if(empty($file_name)){
        throw new Exception('Invalid CDN image path. Can not retrieve the file name.');
      }
      
      Log::info('File to delete: '.$file_name);
      
      $spaceAccessKey  = config( 'services.files_storage.storage_key' );
      $spaceSecretKey  = config( 'services.files_storage.storage_secret' );
      $spaceBucketName = config( 'services.files_storage.storage_bucket_name' );
      $spaceRegion     = config( 'services.files_storage.storage_server_region' );
      
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
    }catch (Exception $exception){
      Log::error($exception->getMessage());
    }
  }
  
  /**
   * Down file from path provided
   *
   * @param string $url
   * @param string $destination
   *
   * @return CraydelInternalResponseHelper
   * @throws Exception
   */
  public static function _downloadFile(string $url, string $destination): CraydelInternalResponseHelper
  {
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
      throw new Exception(curl_error($ch));
    }
    
    curl_close($ch);
    fclose($fp);
    
    return (new CraydelInternalResponseHelper(
      true,
      'Downloaded',[
        'staged_file_path' => $_destination
      ]
    ));
  }
  
  /**
   * Validate base64 image
   *
   * @param string $data
   * @param array $allowed_file_extensions
   * @return CraydelInternalResponseHelper
   */
  public static function validateBase64Image(string $data, array $allowed_file_extensions): CraydelInternalResponseHelper
  {
    try{
      $raw_image_data = $data;
      $data = explode(':', $data);
      
      if(strcmp(CraydelHelperFunctions::toCleanString($data[0]), 'data') !== 0){
        throw new Exception("Invalid base64 image data");
      }
      
      $data = explode(';', $data[1]);
      $file_extension = $data[0] ?? null;
      
      if(CraydelHelperFunctions::isNull($file_extension)){
        throw new Exception("Unable get the file extension");
      }
      
      if(in_array(strtolower($file_extension), $allowed_file_extensions)){
        throw new Exception("");
      }
      
      $image_data = $data[1] ?? null;
      $image_data = CraydelHelperFunctions::toCleanString($image_data);
      
      if(CraydelHelperFunctions::isNull($image_data)){
        throw new Exception("Missing image data");
      }
      
      $image_contents = base64_decode($image_data);
      
      if ($image_contents === false) {
        throw new Exception("The image data is not a valid base64 string");
      }
      
      $image_info = explode(";base64,", $raw_image_data);
      $image_extension = str_replace('data:image/', '', $image_info[0]);
      $image = str_replace(' ', '+', $image_info[1]);
      $image_name = "temp-image-".Str::random(10).".".$image_extension;
      Storage::disk('temp_folder')->put($image_name, base64_decode($image));
      
      if(!Storage::disk('temp_folder')->exists($image_name)){
        throw new Exception("Unable to save the uploaded file");
      }
      
      return (new CraydelInternalResponseHelper(
        true,
        'Image has been validated',(object)[
        'image_path' => Storage::disk('temp_folder')->path($image_name)
      ]
      ));
    }catch (Exception $exception){
      Log::error($exception->getMessage());
      
      return (new CraydelInternalResponseHelper(
        false,
        $exception->getMessage()
      ));
    }
  }
}
