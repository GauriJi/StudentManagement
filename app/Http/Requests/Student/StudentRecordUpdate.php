<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Qs;

class StudentRecordUpdate extends FormRequest
{

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
            'name' => 'required|string|min:6|max:150',
            'gender' => 'required|string',
            'dob' => 'required',
            'phone' => 'sometimes|nullable|string|min:6|max:20',
            'email' => 'required|email|max:100|unique:users,id',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,gif,png,jpg|max:2048',
            'address' => 'required|string|min:6|max:120',
            'bg_id' => 'sometimes|nullable',
            'my_class_id' => 'required',
            'section_id' => 'required',
            'state_id' => 'required',
            'lga_id' => 'required',
            'nal_id' => 'required',
            'dorm_id' => 'sometimes|nullable',
            'city' => 'sometimes|nullable|string',
            'aadhar_card' => 'sometimes|nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'prev_marksheet' => 'sometimes|nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
            'birth_certificate' => 'sometimes|nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'father_name' => 'required|string|max:100',
            'mother_name' => 'required|string|max:100',
            'father_occupation' => 'required|string|max:100',
            'yearly_income' => 'required|numeric|min:0',
        ];
    }

    public function attributes()
    {
        return  [
            'nal_id' => 'Nationality',
            'dorm_id' => 'Dormitory',
            'state_id' => 'State',
            'lga_id' => 'LGA',
            'bg_id' => 'Blood Group',
            'my_class_id' => 'Class',
            'section_id' => 'Section',
        ];
    }

    protected function getValidatorInstance()
    {
        $input = $this->all();

        $this->getInputSource()->replace($input);

        return parent::getValidatorInstance();
    }
}
