<?php

namespace App\Models;

use App\Base\BaseModel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Member extends BaseModel
{
    use CrudTrait;

    static $degree_options=[1=>'PhD/ DE/ Doctorate',2=>'Post Graduate/ Masters',3=>'Undergraduate',4=>'Other Diploma/ Professional Courses'];
    static $school_options=[1=>'School of Engineering and Technology',2=>'School of Environment, Resources and Development',3=>'School of Management',4=>'Other'];

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'members'; 
    protected $primaryKey = 'id';
    // public $timestamps = false;

    public static $status = [1=>'Applied',2=>'Under Review',3=>'Approved'];
    protected $guarded = ['id'];
    protected $fillable = ['gender_id','dob_ad','dob_bs','nrn_number','full_name','photo_path','current_country_id','city_of_residence','ward',
                        'is_other_country','country_id','province_id','district_id','local_level_id','current_province_id','current_district_id','current_local_level_id',
                        'current_organization','past_organization','expertise','linkedin_profile_link','link_to_google_scholar','current_ward',
                        'mailing_address','phone','email','status','document_path','highest_degree','ait_study_details','bio','is_agreed_and_submitted',
                        'approved_date','token'];
    // protected $hidden = [];
    // protected $dates = [];

    protected $casts = [
        'document_path' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function genderEntity()
    {
        return $this->belongsTo(MstGender::class,'gender_id','id');
    }
    public function currentProvinceEntity()
    {
        return $this->belongsTo(MstFedProvince::class,'current_province_id','id');
    }
    public function currentDistrictEntity()
    {
        return $this->belongsTo(MstFedDistrict::class,'current_district_id','id');
    }
    public function currentLocalLevelEntity()
    {
        return $this->belongsTo(MstFedLocalLevel::class,'current_local_level_id','id');
    }
    public function currentCountryEntity()
    {
        return $this->belongsTo(Country::class,'current_country_id','id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function fullName()
    {
        $name = [ $this->first_name,$this->middle_name,$this->last_name];

        return implode(' ',$name);

    }

    public function dob()
    {
        return $this->dob_bs."\n".$this->dob_ad; 
    }

    public function mailingAddress()
    {
        return wordwrap($this->mailing_address,70,"\n",false);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setPhotoPathAttribute($value){
        $attribute_name = "photo_path";
        $disk = "uploads";

        $member_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'Members_photo/###member_ID###/';
        $destination_path = str_replace("###member_ID###", $member_id, $path);

        // dd($destination_path);


        if ($value==null) {
            // delete the image from disk
            \Storage::disk($disk)->delete($this->{$attribute_name});

            // set null in the database column
            $this->attributes[$attribute_name] = null;
        }


        if (\Str::startsWith($value, 'data:image'))
        {
            // 0. Make the image
            $image = Image::make($value)->encode('jpg', 90);
            // 1. Generate a filename.
            $filename = md5($value.time()).'.jpg';
            // 2. Store the image on disk.
            \Storage::disk($disk)->put($destination_path.$filename, $image->stream());
            // 3. Save the public path to the database
        // but first, remove "public/" from the path, since we're pointing to it from the root folder
        // that way, what gets saved in the database is the user-accesible URL
            // $public_destination_path = Str::replaceFirst('public/', '', $destination_path);
            $this->attributes[$attribute_name] = $destination_path.$filename;
        }
        // $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
    }



    //upload document 
    public function setDocumentPathAttribute($value)
    {
        $attribute_name = "document_path";
        $disk = "uploads";

        $member_id = (isset(request()->id) ? request()->id : 0);
        $path  = 'Members_Document/###member_ID###/';
        $destination_path = str_replace("###member_ID###", $member_id, $path);

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);   

    }

    public static function boot()
    {
        parent::boot();
        static::deleted(function ($obj) {
            \Storage::disk('uploads')->delete($obj->photo_path);
        });
    }
}
