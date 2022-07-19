<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Traits\CanCache;
use App\Http\Controllers\Traits\CanLog;
use App\Models\AcademicDiscipline;
use App\Models\CareerPathway;
use App\Models\Cluster;
use App\Models\Countries;
use App\Models\Course;
use App\Models\EducationType;
use App\Models\Subject;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\CanRespond;

class ClusterSubjectHelper
{
    use CanCache, CanLog, CanRespond;

    /**
     * Get  All Subjects
     */

    public static function subjects(): ?array
    {
        try {
            $subjects = self::cache('_subjects_list');
            if (is_null($subjects)) {
                $subjects = DB::table((new Subject())->getTable())
                    ->where('is_published', '=', 1)
                    ->orderBy('id')
                    ->get([
                        'id', 'subject_name'
                    ])->toArray();

                self::cache('_subjects_list', $subjects);
            }
            return $subjects;
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }

    public static function educations(): ?array
    {
        try {
            $education_types_list = self::cache('_education_types_list');

            if (is_null($education_types_list)) {
                $education_types_list = DB::table((new EducationType())->getTable())
                    ->where('is_published', '=', 1)
                    ->orderBy('id')
                    ->get([
                        'id', 'education_type_name'
                    ])->toArray();

                self::cache('_education_types_list', $education_types_list);
            }
            return $education_types_list;
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }

    public static function countries(): ?array
    {
        try {
            $countries_list = self::cache('_countries_list');

            if (is_null($countries_list)) {
                $countries_list = DB::table((new Countries())->getTable())
                    ->orderBy('id')
                    ->get([
                        'id', 'name'
                    ])->toArray();

                self::cache('_countries_list', $countries_list);
            }
            return $countries_list;
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }

    public static function clusters(): ?array
    {
        try {
            $clusters_list = self::cache('_clusters_list');

            if (is_null($clusters_list)) {
                $clusters_list = DB::table((new Cluster())->getTable())
                    ->where('is_published', '=', 1)
                    ->orderBy('id')
                    ->get([
                        'id', 'cluster_name'
                    ])->toArray();

                self::cache('_clusters_list', $clusters_list);
            }
            return $clusters_list;
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }

    /**
     * Get supported careers pathways
     */
    public static function careers_pathways(): CraydelInternalResponseHelper
    {
        try {
            $client = new Client();

            $get_careers_pathways_list = $client->get(
                config('services.craydel_services.career_pathways_service.career_pathways_service_url')
            );

            if ($get_careers_pathways_list->getReasonPhrase() === 'OK') {
                return (new self())->internalResponse(
                    new CraydelInternalResponseHelper(
                        true,
                        'Listed',
                        call_user_func(function () use ($get_careers_pathways_list) {
                            $data = json_decode($get_careers_pathways_list->getBody()->getContents());

                            if (isset($data->status) && $data->status == true) {
                                return $data->data ?? null;
                            } else {
                                return null;
                            }
                        })
                    )
                );
            } else {
                throw new \Exception('Unable to get the list of supported countries via RPC.');
            }
        } catch (\Exception $exception) {
            (new self())->logException($exception);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $exception->getMessage()
            ));
        } catch (GuzzleException $e) {
            (new self())->logException($e);

            return (new self())->internalResponse(new CraydelInternalResponseHelper(
                false,
                $e->getMessage()
            ));
        }
    }
    public static function courses(): ?array
    {
        try {
            $courses_list = self::cache('_courses_list');

            if (is_null($courses_list)) {
                $courses_list = DB::table((new AcademicDiscipline())->getTable())
                    ->where('status', '=', 1)
                    ->orderBy('id')
                    ->get([
                        'id', 'discipline_name'
                    ])->toArray();

                self::cache('_courses_list', $courses_list);
            }
            return $courses_list;
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }
    public static function pathways(): ?array
    {
        try {
            $pathways = self::cache('_pathways_list');
            if (is_null($pathways)) {
                $pathways = DB::table((new CareerPathway())->getTable())
                    ->orderBy('id')
                    ->get([
                        'id', 'career_pathway_name'
                    ])->toArray();

                self::cache('_pathways_list', $pathways);
            }
            return $pathways;
        } catch (\Exception $exception) {
            (new self())->logException($exception);
            return null;
        }
    }

}
