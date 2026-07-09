<?php

namespace App\Containers\SharedSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UploadImageHighlightAmenityRequest extends ParentRequest
{
    protected array $access = [
        'permissions' => '',
        'roles' => '',
    ];

    /**
     * Id's that needs decoding before applying the validation rules.
     */
    protected array $decode = [
        // Không decode vì sử dụng UUID
        // Không decode vì sử dụng UUID
    ];

    /**
     * Defining the URL parameters allows applying validation rules on them.
     */
    protected array $urlParameters = [
        'highlight_amenity_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'highlight_amenity_id' => 'required|uuid|exists:room_highlight_amenities,id',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
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
            'highlight_amenity_id.required' => 'Highlight Amenity ID is required',
            'highlight_amenity_id.uuid' => 'Highlight Amenity ID must be a valid UUID',
            'highlight_amenity_id.exists' => 'Highlight Amenity not exists',
            'images.required' => 'At least one image is required',
            'images.array' => 'Images must be an array',
            'images.min' => 'At least one image is required',
            'images.*.required' => 'Each image is required',
            'images.*.image' => 'File must be an image',
            'images.*.mimes' => 'Image must be jpeg, png, jpg, gif, or webp format',
        ];
    }
}
