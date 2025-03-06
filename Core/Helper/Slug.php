<?php

namespace Core\Helper;

class Slug {
    /**
     * Chuyển đổi chuỗi thành slug
     *
     * @param string $string Chuỗi gốc (VD: "Sản phẩm mới 2024!")
     * @param string $separator Dấu phân cách slug (mặc định là "-")
     * @return string Chuỗi slug (VD: "san-pham-moi-2024")
     */
    public static function createSlug(string $string, string $separator = '-'): string {
        // Chuyển đổi sang chữ thường
        $string = mb_strtolower($string, 'UTF-8');
        
        // Thay thế các ký tự có dấu thành không dấu
        $string = self::removeAccents($string);
        
        // Xóa các ký tự không phải chữ cái, số hoặc khoảng trắng
        $string = preg_replace('/[^a-z0-9\s]/u', '', $string);
        
        // Thay thế khoảng trắng bằng dấu phân cách
        $string = preg_replace('/\s+/', $separator, $string);
        
        // Xóa dấu phân cách ở đầu và cuối chuỗi
        return trim($string, $separator);
    }
    
    /**
     * Loại bỏ dấu tiếng Việt
     *
     * @param string $string Chuỗi gốc
     * @return string Chuỗi không dấu
     */
    private static function removeAccents(string $string): string {
        $accents = [
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ'
        ];
        
        $noAccents = [
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        ];
        
        return str_replace($accents, $noAccents, $string);
    }
}
