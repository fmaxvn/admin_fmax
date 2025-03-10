<?php

namespace Core\Helper;

class Validator
{
    private array $errors = [];

    /**
     * Kiểm tra dữ liệu theo rules
     *
     * @param array $data Dữ liệu đầu vào ['key' => 'value']
     * @param array $rules Rule áp dụng ['key' => ['required', 'email', 'min:3']]
     *
     * @example
     * ```php
     * $data = ['name' => 'John', 'email' => 'john@example.com'];
     * $rules = ['name' => ['required', 'min:3'], 'email' => ['required', 'email']];
     * $validator = new Validator();
     * $validator->validate($data, $rules);
     * if ($validator->fails()) print_r($validator->errors());
     * ```
     */
    public function validate(array $data, array $rules): void
    {
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleValue] = explode(':', $rule);
                    $this->applyRule($field, $value, $ruleName, $ruleValue);
                } else {
                    $this->applyRule($field, $value, $rule);
                }
            }
        }
    }

    /**
     * Áp dụng từng rule lên dữ liệu
     *
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị của trường
     * @param string $rule Tên rule (VD: 'required', 'min', 'email')
     * @param mixed|null $ruleValue Giá trị rule (VD: min:3 -> ruleValue = 3)
     */
    private function applyRule(string $field, $value, string $rule, $ruleValue = null): void
    {
        switch ($rule) {
            case 'required': // Trường không được để trống
                if (empty($value) && $value !== '0') {
                    $this->errors[$field][] = "Trường $field là bắt buộc.";
                }
                break;

            case 'email': // Kiểm tra email hợp lệ
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "Trường $field phải là email hợp lệ.";
                }
                break;

            case 'numeric': // Kiểm tra số
                if (!is_numeric($value)) {
                    $this->errors[$field][] = "Trường $field phải là số.";
                }
                break;

            case 'min': // Kiểm tra độ dài tối thiểu
                if (strlen($value) < (int)$ruleValue) {
                    $this->errors[$field][] = "Trường $field phải có ít nhất $ruleValue ký tự.";
                }
                break;

            case 'max': // Kiểm tra độ dài tối đa
                if (strlen($value) > (int)$ruleValue) {
                    $this->errors[$field][] = "Trường $field không được vượt quá $ruleValue ký tự.";
                }
                break;

            case 'regex': // Kiểm tra regex (VD: số điện thoại, username)
                if (!preg_match($ruleValue, $value)) {
                    $this->errors[$field][] = "Trường $field không đúng định dạng.";
                }
                break;
            case 'password': // ✅ Kiểm tra độ mạnh của mật khẩu
                if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+<>?])[A-Za-z\d!@#$%^&*()_+<>?]{8,16}$/', $value)) {
                    $this->errors[$field][] = "Mật khẩu phải từ 8-16 ký tự, có ít nhất 1 chữ hoa, 1 chữ thường, 1 số và 1 ký tự đặc biệt (!@#$%^&*()_+<>?).";
                }
                break;
            case 'in': // Kiểm tra giá trị có trong danh sách cho phép (VD: sex = 0,1) , VD: 'type' => ['required', 'in:0,1'],
                if (!in_array($value, explode(',', $ruleValue))) {
                    $this->errors[$field][] = "Trường $field phải là một trong các giá trị: $ruleValue.";
                }
                break;

            case 'date': // Kiểm tra ngày hợp lệ
                if (!strtotime($value)) {
                    $this->errors[$field][] = "Trường $field phải là một ngày hợp lệ.";
                }
                break;

            case 'before_or_equal': // Kiểm tra ngày không vượt quá ngày hiện tại
                if (strtotime($value) > strtotime($ruleValue)) {
                    $this->errors[$field][] = "Trường $field không được lớn hơn $ruleValue.";
                }
                break;
        }
    }

    /**
     * Kiểm tra nếu có lỗi
     *
     * @example
     * ```php
     * if ($validator->fails()) { print_r($validator->errors()); }
     * ```
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Lấy danh sách lỗi
     *
     * @return array Mảng chứa danh sách lỗi
     *
     * @example
     * ```php
     * print_r($validator->errors());
     * ```
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
