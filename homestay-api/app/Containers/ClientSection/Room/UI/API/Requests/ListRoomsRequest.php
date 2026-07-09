<?php

namespace App\Containers\ClientSection\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

class ListRoomsRequest extends ParentRequest
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
    protected array $decode = [];

    /**
     * Defining the URL parameters (e.g, `/user/{id}`) allows applying
     * validation rules on them and allows accessing them like request data.
     */
    protected array $urlParameters = [];

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0',
            'capacity' => 'sometimes|integer|min:1|max:20',
            'room_type_id' => 'sometimes|integer|exists:room_types,id',
            'check_in' => 'sometimes|date|after_or_equal:today',
            'check_out' => 'sometimes|date|after:check_in',
            'query' => 'sometimes|string|max:255',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:price,name,capacity,created_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'projector' => 'sometimes|boolean',
            'balcony' => 'sometimes|boolean',
            'duplex' => 'sometimes|boolean',
            'bathtub' => 'sometimes|boolean',
            'self_checkin' => 'sometimes|boolean',
            'parking_for_car' => 'sometimes|boolean',
            'is_loved_by_everyone' => 'sometimes|boolean',
            'bedroom_count' => 'sometimes|integer|min:1|max:50',
            'bed_count' => 'sometimes|integer|min:1|max:50',
            'bathroom_count' => 'sometimes|integer|min:1|max:50',
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
            'max_price.gte' => 'Giá tối đa phải lớn hơn hoặc bằng giá tối thiểu.',
            'check_in.after_or_equal' => 'Ngày check-in phải từ hôm nay trở đi.',
            'check_out.after' => 'Ngày check-out phải sau ngày check-in.',
            'capacity.min' => 'Số người tối thiểu là 1.',
            'capacity.max' => 'Số người tối đa là 20.',
        ];
    }
}
