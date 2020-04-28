<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'image' => 'File vừa chọn có định dạng không hợp lệ.',
    'available_quantity' => 'Số lượng nhập không phù hợp',
    'adjusted_quantity' => 'Số lượng nhập không phù hợp',
    'required' => 'Không thể để trống :attribute.',
    'unique' => ':attribute đã tồn tại.',
    'max' => [
        'string' => ':attribute không thể chứa nhiều hơn :max kí tự.',
        'numeric' => ':attribute không thể lớn hơn :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'alpha' => ':attribute chỉ được chứa kí tự chữ',
    'in' => ':attribute không phù hợp.',
    'integer' => ':attribute phải là số nguyên.',

    'between' => [
        'numeric' => ':attribute phải có giá trị từ :min đến :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],


    'custom' => [
        'user_id' => [
            'regex' => 'Mã nhân viên chỉ có thể chứa kí tự số',
        ],
    ],
    'exists' => ':attribute vừa chọn không hợp lệ hoặc không tìm thấy.',
    'attributes' => [
        'row' => 'Số tầng',
        'col' => 'Số cột',
        'ordered_items.*.ordered_quantity' => 'Số lượng đặt hàng',
        'ordered_quantity' => 'Số lượng đặt hàng',
        'demanded_quantity' => 'Số lượng cấp phát',
        'imported_quantity' => 'Số lượng nhập',
        'comment' => 'Ghi chú',
        'user_pk' => 'Nhân viên',
        'device_pk' => 'Thiết bị',
        'block_pk' => 'Dãy kho',
        'workplace_pk' => 'Bộ phận',
        'workplace_name' => 'Tên bộ phận',
        'user_id' => 'Mã nhân viên',
        'user_name' => 'Tên nhân viên',
        'device_id' => 'Mã thiết bị',
        'device_name' => 'Tên thiết bị',
        'role' => 'Quyền hạn',
        'address' => 'Địa chỉ',
        'phone' => 'Số điện thoại',
        'customer_pk' => 'Khách hàng',
        'customer_name' => 'Tên khách hàng',
        'customer_id' => 'Mã khách hàng',
        'supplier_pk' => 'Nhà cung cấp',
        'supplier_name' => 'Tên nhà cung cấp',
        'supplier_id' => 'Mã nhà cung cấp',
        'case_pk' => 'Đơn vị chứa'
    ],










    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',

    'alpha_dash' => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',

    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field is required.',
    'gt' => [
        'numeric' => ':attribute phải lớn hơn :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'in_array' => 'The :attribute field does not exist in :other.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],

    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'present' => 'The :attribute field must be present.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => ':attribute phải có độ dài :size kí tự.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',








];
