<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Video;

class UpdateVideo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $video = $this->route('video');
        
        return $video && $this->user()->can( 'update', $video );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|min:5|max:255',
            'description' => 'required|min:1|max:255',
            'video'       => 'mimes:mp4',
            'image'       => 'mimes:jpg,png,jpeg'
        ];
    }
}
