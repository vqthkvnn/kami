<?php


namespace App\Http\Requests;


class PostRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_content' => 'required',
            'subject_id' => 'required',
            'post_title' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'post_content' => 'Content',
            'subject_id' => 'Subject ID',
            'post_title' => 'Title',
        ];
    }
}
