<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParcelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tracking_no' => 'required|string|max:50|unique:parcels',
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'description' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'invoice' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'office_id' => 'required|exists:offices,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tracking_no.required' => 'Tracking number is required.',
            'tracking_no.unique' => 'This tracking number is already registered.',
            'tracking_no.max' => 'Tracking number cannot exceed 50 characters.',
            'receiver_name.required' => 'Receiver name is required.',
            'receiver_name.max' => 'Receiver name cannot exceed 255 characters.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.max' => 'Phone number cannot exceed 20 characters.',
            'description.required' => 'Description is required.',
            'description.max' => 'Description cannot exceed 500 characters.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'invoice.file' => 'Invoice must be a valid file.',
            'invoice.mimes' => 'Invoice must be a PDF, JPG, JPEG, or PNG file.',
            'invoice.max' => 'Invoice file size cannot exceed 2MB.',
            'office_id.required' => 'Please select a drop point.',
            'office_id.exists' => 'Selected drop point is invalid.',
        ];
    }
} 