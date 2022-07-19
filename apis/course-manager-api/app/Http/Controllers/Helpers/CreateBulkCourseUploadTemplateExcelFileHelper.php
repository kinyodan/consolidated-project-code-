<?php
namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanLog;
use Exception;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CreateBulkCourseUploadTemplateExcelFileHelper
{
    use CanLog;

    /**
     * @var string $file_name
    */
    protected $file_name;

    /**
     * @var Spreadsheet $spreadsheet
    */
    protected $spreadsheet;

    /**
     * @var Worksheet $activeSheet
    */
    protected $activeSheet;

    /**
     * @var string $active_sheet_title
    */
    protected $active_sheet_title;

    /**
     * @var array $headers
    */
    protected $headers;

    /**
     * @var int $maximum_rows_allowed
    */
    protected $maximum_rows_allowed;

    /**
     * @var array $columns_with_manual_width
    */
    protected $columns_with_manual_width = [];

    /**
     * Create template
    */
    public function __construct(?string $file_name = null)
    {
        try{
            $this->file_name = !is_null($file_name) && !empty($file_name) ? trim($file_name) : Str::random(15);
            $this->maximum_rows_allowed = 10000;
            $this->active_sheet_title = 'Courses';
            $this->spreadsheet = new Spreadsheet();
            $this->spreadsheet->setActiveSheetIndex(0);
            $this->activeSheet = $this->spreadsheet->getActiveSheet();
            $this->activeSheet->setTitle($this->active_sheet_title);

            return $this;
        }catch (Exception $exception){
            $this->logException($exception);
            return $this;
        }
    }

    /**
     * Add header
    */
    public function headers(?array $headers): CreateBulkCourseUploadTemplateExcelFileHelper
    {
        try{
            if(!is_array($headers) && count($headers) <0 ){
                throw new Exception("Invalid headers");
            }

            $this->headers = $headers;

            $row = 1;
            $column_count = 1;

            foreach ($headers as $key => $value){
                if(intval($key) > 0){
                    $column_count++;
                }

                $cell_number = CraydelHelperFunctions::convertNumberToAlphabet($column_count);

                if(!empty($cell_number)){
                    $this->activeSheet->setCellValue($cell_number.''.$row, $value);
                }
            }

            return $this;
        }catch (Exception $exception){
            $this->logException($exception);
            return $this;
        }
    }

    /**
     * Add a new column
     */
    public function setColumnDataList(?string $cell_name, ?array $cell_validation_values = null): CreateBulkCourseUploadTemplateExcelFileHelper
    {
        try{
            if(empty($cell_name)){
                throw new Exception('Missing cell name');
            }

            if(!is_null($cell_validation_values) && !is_array($cell_validation_values)){
                throw new Exception("Invalid cell values, only support array values.");
            }

            $cell_number = CraydelHelperFunctions::convertNumberToAlphabet(
                (array_search($cell_name, $this->headers) + 1)
            );

            if(is_null($cell_number)){
                throw new Exception("Could not locate the cell with header: ".$cell_name);
            }

            $row_control = 2;

            while($row_control <= $this->maximum_rows_allowed){
                $current_cell = $this->activeSheet->getCell($cell_number.$row_control);

                if(is_null($current_cell)){
                    throw new Exception("Unable to get the cell number: ".$cell_name);
                }

                if(!is_null($cell_validation_values)){
                    $current_cell
                        ->getDataValidation()
                        ->setType(DataValidation::TYPE_LIST)
                        ->setFormula1('"'.implode(',', $cell_validation_values).'"')
                        ->setAllowBlank(false)
                        ->setShowDropDown(true)
                        ->setShowInputMessage(true)
                        ->setPromptTitle('Note')
                        ->setPromptTitle('Must select one from the drop down options.')
                        ->setShowErrorMessage(true)
                        ->setErrorStyle(DataValidation::STYLE_STOP)
                        ->setErrorTitle('Invalid input')
                        ->setError("Pick from the drop down list.");
                }
                $row_control++;
            }

            return $this;
        }catch (Exception $exception){
            $this->logException($exception);
            return $this;
        }
    }

    /**
     * Enforce sell validation
    */
    public function forceCellToBeDecimal(?string $cell_name, ?string $allowed_data_type, ?float $minimum_value = 1, ?float $maximum_value = 100000): CreateBulkCourseUploadTemplateExcelFileHelper
    {
        try{
            if(empty($cell_name)){
                throw new Exception('Missing cell name');
            }

            if (empty($allowed_data_type)){
                throw new Exception("Data validation type is not defined");
            }

            $cell_number = CraydelHelperFunctions::convertNumberToAlphabet(
                (array_search($cell_name, $this->headers) + 1)
            );

            if(is_null($cell_number)){
                throw new Exception("Could not locate the cell with header: ".$cell_name);
            }

            $row_control = 2;

            while($row_control <= $this->maximum_rows_allowed) {
                $current_cell = $this->activeSheet->getCell($cell_number . $row_control);

                if (is_null($current_cell)) {
                    throw new Exception("Unable to get the cell number: " . $cell_name);
                }

                $current_cell
                    ->getDataValidation()
                    ->setType($allowed_data_type)
                    ->setOperator(DataValidation::OPERATOR_BETWEEN)
                    ->setFormula1(floatval($minimum_value))
                    ->setFormula1(floatval($maximum_value))
                    ->setAllowBlank(false)
                    ->setShowInputMessage(true)
                    ->setPromptTitle('Note')
                    ->setShowErrorMessage(true)
                    ->setErrorStyle(DataValidation::STYLE_STOP)
                    ->setErrorTitle('Invalid input')
                    ->setError("Enter a valid : ".$allowed_data_type." ensure that value enter is between ".$minimum_value." - ".$maximum_value);

                $row_control++;
            }

            return $this;
        }catch (Exception $exception){
            $this->logException($exception);
            return $this;
        }
    }

    /**
     * Set cell width
     *
     * @param string|null $cell_name
     * @param int|null $width
     *
     * @return CreateBulkCourseUploadTemplateExcelFileHelper
     */
    public function setCellWidth(?string $cell_name, ?int $width): CreateBulkCourseUploadTemplateExcelFileHelper
    {
        try{
            if(empty($cell_name)){
                throw new Exception('Missing cell name');
            }

            if(empty(intval($width))){
                throw new Exception('Missing cell width');
            }

            $cell_number = CraydelHelperFunctions::convertNumberToAlphabet(
                (array_search($cell_name, $this->headers) + 1)
            );

            if(is_null($cell_number)){
                throw new Exception("Could not locate the cell with header: ".$cell_name);
            }

            $this
                ->activeSheet
                ->getColumnDimension($cell_number)
                ->setWidth($width);

            array_push($this->columns_with_manual_width, $cell_name);

            return $this;
        }catch (\Exception $exception){
            $this->logException($exception);
            return $this;
        }
    }

    /**
     * Resize all columns
    */
    public function autoFit(): CreateBulkCourseUploadTemplateExcelFileHelper
    {
        try{
            $column_count = 1;

            for($i = 1; $i <= count($this->headers); $i++){
                $cell_number = CraydelHelperFunctions::convertNumberToAlphabet($i);

                if(!empty($cell_number)){
                    $this
                        ->activeSheet
                        ->getColumnDimension($cell_number)
                        ->setAutoSize(true);
                }
            }

            for($j = 1; $j <= count($this->headers); $j++){
                $this
                    ->activeSheet
                    ->getStyle(CraydelHelperFunctions::convertNumberToAlphabet($j))
                    ->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_TOP);
            }

            return $this;
        }catch (Exception $exception){
            $this->logException($exception);
            return $this;
        }
    }

    /**
     * Create columns
    */
    public function create()
    {
        try{
            $writer = new Xlsx($this->spreadsheet);
            $file_path = public_path().DIRECTORY_SEPARATOR.$this->file_name.'.xlsx';
            $writer->save($file_path);
        }catch (Exception $exception){
            $this->logException($exception);
            return null;
        }
    }

    /**
     * Check if the excel file
    */
    public static function validateFileHeaders(?string $file_path, ?array $expected_file_headers): CraydelInternalResponseHelper
    {
        try {
            if(!file_exists($file_path)){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.unable_to_read_file'));
            }

            if(!is_array($expected_file_headers) || count($expected_file_headers) <= 0){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.missing_file_headers_to_compare'));
            }

            $reader = IOFactory::createReaderForFile($file_path);
            $file = $reader->load($file_path);

            if(is_null($file)){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.unable_to_read_file'));
            }

            $sheet = $file->getSheet(0);

            if(is_null($sheet)){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.unable_to_read_the_first_sheet'));
            }

            $data = $sheet->toArray();

            if(count($data) <= 0){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.empty_excel_sheet_uploaded'));
            }

            $headers = $data[0] ?? null;

            if(is_null($headers)){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.empty_excel_sheet_uploaded'));
            }

            $headers = collect($headers)->reject(function ($item){
                return is_null($item) || empty($item);
            })->values()->toArray();

            if(!CraydelHelperFunctions::compare2Arrays($headers, $expected_file_headers)){
                throw new Exception(LanguageTranslationHelper::translate('courses.errors.import.invalid_file_headers'));
            }

            return new CraydelInternalResponseHelper(
                true,
                "Validated"
            );
        }catch (Exception $exception){
            (new self())->logException($exception);

            return new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            );
        }
    }
}
