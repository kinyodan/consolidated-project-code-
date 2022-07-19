<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use function app;

class CraydelHelperFunctions
{
  use CanLog;
  
  /**
   * Sanitize number
   *
   * @param string|null $rawNumber
   * @return string
   */
  public static function toNumbers(?string $rawNumber): string
  {
    try {
      return trim(preg_replace("/[^0-9]/", "", $rawNumber));
    } catch (Exception $exception) {
      return str_replace(' ', '', trim($rawNumber));
    }
  }
  
  /**
   * Sanitize email
   *
   * @param string|null $rawEmailAddress
   * @return string
   */
  public static function toEmailAddress(?string $rawEmailAddress): string
  {
    try {
      return strtolower(trim($rawEmailAddress));
    } catch (Exception $exception) {
      return strtolower(trim($rawEmailAddress));
    }
  }
  
  /**
   * Sanitize string
   *
   * @param string|null $string $string
   * @return string|null
   */
  public static function toCleanString(?string $string): ?string
  {
    try {
      $string = trim(urldecode($string));
      $string = htmlspecialchars_decode($string);
      $string = preg_replace('/\s+/', ' ', $string);
      
      return !empty($string) ? $string : null;
    } catch (Exception $exception) {
      return trim(urldecode($string));
    }
  }
  
  /**
   * Sanitize email
   *
   * @param string|null $rawEmailAddress
   * @param bool $strict
   * @return string|null
   */
  public static function makeCleanEmailAddress(string $rawEmailAddress, bool $strict = true): ?string
  {
    try {
      $rawEmailAddress = strtolower(trim($rawEmailAddress));
      
      return !empty($rawEmailAddress) ? $rawEmailAddress : null;
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return null;
    }
  }
  
  /**
   * Process name abbreviation
   *
   * @param string|null $name
   * @return string|null
   */
  public static function makeAcronym(?string $name): ?string
  {
    try {
      if (empty(trim($name))) {
        return null;
      }
      
      preg_match('#\((.*?)\)#', $name, $match);
      
      if (isset($match[1]) && !empty($match[1])) {
        return strtoupper(strtolower($match[1]));
      };
      
      if (!empty($name)) {
        $name = self::toCleanString($name);
        return preg_replace('/\b(\w)|./', '$1', $name);
      }
      
      return $name;
    } catch (Exception $exception) {
      return $name;
    }
  }
  
  /**
   * Get first name from full names
   *
   * @param string|null $fullNames
   * @return string|null
   */
  public static function makeFirstName(?string $fullNames): ?string
  {
    try {
      if (!empty($fullNames)) {
        $_fullNames = explode(' ', self::toCleanString($fullNames));
        
        return isset($_fullNames[0]) && !empty($_fullNames[0]) ? ucfirst($_fullNames[0]) : "";
      }
      
      return $fullNames;
    } catch (Exception $exception) {
      return $fullNames;
    }
  }
  
  /**
   * Get other names from full names
   *
   * @param string|null $fullNames
   * @return string|null
   */
  public static function makeOtherNames(?string $fullNames): ?string
  {
    try {
      if (!empty($fullNames)) {
        $fullNames = self::toCleanString($fullNames);
        
        return call_user_func(function () use ($fullNames) {
          return ucfirst(trim(substr($fullNames, strpos($fullNames, ' '))));
        });
      }
      
      return $fullNames;
    } catch (Exception $exception) {
      return $fullNames;
    }
  }
  
  /**
   * Hide email details
   *
   * @param string|null $emailAddress
   * @return string
   */
  public static function makeObfuscatedEmailAddress(?string $emailAddress): string
  {
    try {
      if (!empty($emailAddress)) {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
          $emailAddressSegments = explode("@", $emailAddress);
          $emailAddressSegments[0] = substr($emailAddressSegments[0], 0, 2) . str_repeat("*", strlen($emailAddressSegments[0]) - 2) . substr($emailAddressSegments[0], -1);
          $emailAddressSegments[1] = $emailAddressSegments[1] ?? "";
          return implode("@", $emailAddressSegments);
        } else {
          return substr($emailAddress, 0, -4) . "****";
        }
      } else {
        return "";
      }
    } catch (Exception $exception) {
      return $emailAddress;
    }
  }
  
  /**
   * Generate random fixed length number
   *
   * @param int|null $length
   * @return mixed
   */
  public static function makeRandomNumber(?int $length): int
  {
    try {
      if (!empty($length)) {
        if (intval($length) > 16) {
          $length = 16;
        }
        
        $returnString = mt_rand(1, 9);
        
        while (strlen($returnString) < $length) {
          $returnString .= mt_rand(0, 9);
        }
        
        return !empty($returnString) ? $returnString : rand(intval((PHP_INT_MAX / 2)), PHP_INT_MAX);
      } else {
        return rand(intval((PHP_INT_MAX / 2)), PHP_INT_MAX);
      }
    } catch (Exception $exception) {
      return rand(intval((PHP_INT_MAX / 2)), PHP_INT_MAX);
    }
  }
  
  /**
   * Generate a random token
   *
   * @param int $length
   * @param string|null $prefix
   * @param boolean $encode
   * @return string
   */
  public static function makeRandomString(int $length, ?string $prefix = null, ?bool $encode = true): string
  {
    try {
      $length = is_numeric($length) ? $length : 40;
      $length = min($length, 40);
      
      if ($encode === true) {
        return base64_encode(Str::random($length));
      } else {
        if (!is_null($prefix)) {
          return strtoupper($prefix . Str::random($length));
        } else {
          return strtoupper(Str::random($length));
        }
      }
    } catch (Exception $exception) {
      return '';
    }
  }
  
  /**
   * Get file contents
   *
   * @param $filePath
   * @return mixed
   *
   */
  public static function getFileContent($filePath): string
  {
    try {
      if (!empty($filePath) && file_exists($filePath)) {
        return file_get_contents($filePath);
      } else {
        return "";
      }
    } catch (Exception $exception) {
      return "";
    }
  }
  
  /**
   * Generate a slug given a string
   *
   * @param string|null $string $string
   * @param boolean $hash
   * @param array|null $replace
   * @param string|null $delimiter
   *
   * @return string|null
   */
  public static function slugifyString(?string $string, ?bool $hash = false, ?array $replace = array(), ?string $delimiter = '-'): ?string
  {
    try {
      $string = trim($string);
      
      if (empty($string)) {
        return null;
      }
      
      if (!extension_loaded('iconv')) {
        throw new Exception('iconv module not loaded');
      }
      
      $string = urldecode($string);
      $oldLocale = setlocale(LC_ALL, '0');
      setlocale(LC_ALL, 'en_US.UTF-8');
      $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
      
      if (!empty($replace)) {
        $clean = str_replace((array)$replace, ' ', $clean);
      }
      
      $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
      $clean = strtolower($clean);
      $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
      $clean = trim($clean, $delimiter);
      setlocale(LC_ALL, $oldLocale);
      
      $clean = trim($clean);
      $clean = preg_replace('/-+/', '-', $clean);
      
      return $hash == true ? md5($clean) : $clean;
    } catch (Exception $exception) {
      return "";
    }
  }
  
  /**
   * Get the client IP address
   *
   * @return string
   */
  public static function getClientIPAddress(): string
  {
    try {
      foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
          foreach (explode(',', $_SERVER[$key]) as $ip) {
            $ip = trim($ip); // just to be safe
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
              return $ip;
            }
          }
        }
      }
      
      return "";
    } catch (Exception $exception) {
      return "";
    }
  }
  
  /**
   * Generate a strong user password
   *
   * @param int|null $length
   * @param bool $addDashes
   * @param string|null $availableSets
   *
   * @return string
   */
  public static function makeStrongPassword(?int $length = 8, ?bool $addDashes = false, ?string $availableSets = 'luds'): string
  {
    try {
      $sets = array();
      if (str_contains($availableSets, 'l'))
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
      if (str_contains($availableSets, 'u'))
        $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
      if (str_contains($availableSets, 'd'))
        $sets[] = '23456789';
      if (str_contains($availableSets, 's'))
        $sets[] = '!@#$%^&?*()\-_=+{};:,<.>[]';
      
      $all = '';
      $password = '';
      foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
        $all .= $set;
      }
      
      $all = str_split($all);
      for ($i = 0; $i < $length - count($sets); $i++)
        $password .= $all[array_rand($all)];
      
      $password = str_shuffle($password);
      
      if (!$addDashes)
        return $password;
      
      $dash_len = floor(sqrt($length));
      $dash_str = '';
      while (strlen($password) > $dash_len) {
        $dash_str .= substr($password, 0, $dash_len) . '-';
        $password = substr($password, $dash_len);
      }
      $dash_str .= $password;
      
      return $dash_str;
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return Str::random(10);
    }
  }
  
  /**
   * Convert image size to Mbs
   *
   * @param float $bytes
   * @return float|int
   */
  public static function convertBytesToMBs(float $bytes): float
  {
    try {
      $base = log($bytes, 1024);
      $suffixes = array('B', 'K', 'M', 'G', 'T');
      $type = $suffixes[floor($base)] ?? null;
      if (!is_null($type)) {
        $value = 0;
        if ($type == 'B') {
          $value = round($bytes / 1024, 4);
        } elseif ($type == 'K') {
          $value = (floatval($bytes) * 0.001) * 0.001;
        } elseif ($type == 'M') {
          $value = (floatval($bytes) * 0.001) * 0.001;
        } elseif ($type == 'G') {
          $value = ((floatval($bytes) * 0.001) * 0.001) * 1000;
        }
        return $value;
      } else {
        return 0;
      }
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return 0;
    }
  }
  
  /**
   * Get the image aspect ratio
   *
   * @param $width
   * @param $height
   * @return string|null
   */
  public static function ratio($width, $height): ?string
  {
    try {
      $gcd = function ($a, $b) use (&$gcd) {
        return ($a % $b) ? $gcd($b, $a % $b) : $b;
      };
      
      $g = $gcd($width, $height);
      return $width / $g . ':' . $height / $g;
    } catch (Exception $exception) {
      return null;
    }
  }
  
  /**
   * Get the image aspect ratio multiplier
   *
   * @param float|null $width
   * @param float|null $height
   * @return float|null
   */
  public static function imageAspectRationMultiplier(?float $width, ?float $height): ?float
  {
    try {
      $value = function ($width, $height) use (&$value) {
        return ($width % $height) ? $value($height, $width % $height) : $height;
      };
      
      $g = $value($width, $height);
      $r1 = $width / $g;
      $r2 = $height / $g;
      $arr = [$r1, $r2];
      
      natcasesort($arr);
      $arr = array_reverse($arr, true);
      
      return round((floatval($arr[0]) / floatval($arr[1])), 1);
    } catch (Exception $exception) {
      return null;
    }
  }
  
  /**
   * Get the file name from URL
   *
   * @param string|null $url
   * @return ?string
   */
  public static function getFileNameFromURL(?string $url): ?string
  {
    try {
      return basename(parse_url($url, PHP_URL_PATH));
    } catch (Exception $exception) {
      return null;
    }
  }
  
  /**
   * Clean Array Keys
   *
   * @param array|null $dataArray
   * @return array|null
   */
  public static function cleanArrayKeys(?array $dataArray): ?array
  {
    $updatedDataArray = [];
    
    try {
      foreach ($dataArray as $key => $value) {
        $updatedDataArray[str_replace(" ", "_", strtolower($key))] = $value;
      }
      return $updatedDataArray;
    } catch (Exception $exception) {
      return [];
    }
  }
  
  /**
   * Automatically update the environment variables
   *
   * @param array|null $values
   * @return bool
   */
  public static function setEnvironmentValue(?array $values): bool
  {
    try {
      $envFile = app()->basePath('.env');
      $str = file_get_contents($envFile);
      
      if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {
          $str .= "\n";
          $keyPosition = strpos($str, "{$envKey}=");
          $endOfLinePosition = strpos($str, "\n", $keyPosition);
          $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
          
          if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
            $str .= "{$envKey}={$envValue}\n";
          } else {
            $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
          }
        }
      }
      
      $str = substr($str, 0, -1);
      if (!file_put_contents($envFile, $str)) return false;
      
      Artisan::call("config:cache");
      
      return true;
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return false;
    }
  }
  
  /**
   * Check if the URL exists
   */
  public static function urlExists($url): bool
  {
    return curl_init($url) !== false;
  }
  
  /**
   * Is valid details
   *
   * @param string|null $string
   * @return bool
   */
  public static function isJson(?string $string): bool
  {
    try {
      json_decode($string);
      return (json_last_error() == JSON_ERROR_NONE);
    } catch (Exception $exception) {
      (new self())->logException($exception);
      return false;
    }
  }
  
  /**
   * Check if the value is null
   *
   * @return bool
   * @var $input_to_check
   */
  public static function isNull($input_to_check): bool
  {
    $input_to_check = self::toCleanString($input_to_check);
    
    if (strlen($input_to_check) <= 0) {
      return true;
    }
    
    if (is_null($input_to_check)) {
      return true;
    }
    
    if (strcmp('null', (string)$input_to_check) === 0) {
      return true;
    }
    
    return false;
  }
  
  /**
   * Check if the value is a valid email
   */
  public static function isEmail($input_to_check): bool
  {
    $input_to_check = self::toCleanString($input_to_check);
    
    if (!filter_var($input_to_check, FILTER_VALIDATE_EMAIL)) {
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * Check if the value is a valid URL
   */
  public static function isURL($input_to_check): bool
  {
    $input_to_check = self::toCleanString($input_to_check);
    
    if (!filter_var($input_to_check, FILTER_VALIDATE_URL)) {
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * Check if the value is a valid date
   *
   * @param string|null $value
   * @return bool
   */
  public static function isDate(?string $value): bool
  {
    $value = self::toCleanString($value);
    
    if (Carbon::parse($value)) {
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * Create a soft delete name
   */
  public static function createSoftDeleteValue(string $value): string
  {
    if (!empty($value)) {
      $value = $value . '_deleted' . Str::random(5);
    }
    
    return $value;
  }
}
