<?php

namespace App\Containers\ClientSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class UploadRoomImageRequest extends ParentRequest
{
    /**
     * Define which Roles and/or Permissions has access to this request.
     */
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     */
    protected array $decode = [
        // Không decode vì sử dụng UUID
    ];

    /**
     * Defining the URL parameters allows applying validation rules on them.
     */
    protected array $urlParameters = [
        'room_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|uuid|exists:rooms,id',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'is_cover' => 'sometimes|boolean',
            'area_group_id' => 'sometimes|uuid',
            'description' => 'sometimes|string|max:500',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'room_id.required' => 'Room ID is required',
            'room_id.uuid' => 'Room ID must be a valid UUID',
            'room_id.exists' => 'Room không tồn tại',
            'images.required' => 'At least one image is required',
            'images.array' => 'Images must be an array',
            'images.min' => 'At least one image is required',
            'images.*.required' => 'Each image is required',
            'images.*.image' => 'File must be an image',
            'images.*.mimes' => 'Image must be jpeg, png, jpg, gif, or webp format',
        ];
    }
}
