<?php
declare(strict_types=1);

namespace InbesContact;

final class InvalidSubmission extends \RuntimeException {}

function field(array $input, string $key, int $max, bool $required = false, bool $multiline = false): string
{
    $value = $input[$key] ?? '';
    if (!is_string($value) || !mb_check_encoding($value, 'UTF-8')) {
        throw new InvalidSubmission('invalid field');
    }
    $value = trim($value);
    if (mb_strlen($value, 'UTF-8') > $max || ($required && $value === '')) {
        throw new InvalidSubmission('invalid field length');
    }
    $controls = $multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/' : '/[\x00-\x1F\x7F]/';
    if (preg_match($controls, $value)) {
        throw new InvalidSubmission('invalid control characters');
    }
    return $value;
}

function submission(array $input): array
{
    $category = field($input, 'category', 30, true);
    if (!in_array($category, ['商品化の相談', '製品サポート'], true) || ($input['privacy'] ?? '') !== 'on') {
        throw new InvalidSubmission('invalid category or consent');
    }
    if (field($input, 'website', 200) !== '') {
        throw new InvalidSubmission('invalid submission');
    }
    $email = field($input, 'email', 254, true);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidSubmission('invalid email');
    }
    return [
        'category' => $category,
        'company' => field($input, 'company', 120),
        'name' => field($input, 'name', 120, true),
        'email' => $email,
        'tel' => field($input, 'tel', 40),
        'message' => field($input, 'message', 10000, true, true),
    ];
}

function attachment(array $files): ?array
{
    if (count($files) > 1 || (count($files) === 1 && !isset($files['attachment']))) {
        throw new InvalidSubmission('unexpected attachment');
    }
    if (!isset($files['attachment'])) {
        return null;
    }
    $file = $files['attachment'];
    if (!is_array($file) || !isset($file['error']) || !is_int($file['error'])) {
        throw new InvalidSubmission('invalid upload');
    }
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK || !is_string($file['tmp_name'] ?? null) ||
        !is_string($file['name'] ?? null) || !is_uploaded_file($file['tmp_name'])) {
        throw new InvalidSubmission('upload failed');
    }
    $size = filesize($file['tmp_name']);
    if ($size === false || $size < 1 || $size > 10485760) {
        throw new InvalidSubmission('invalid upload size');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $types = [
        'pdf' => ['application/pdf'],
        'jpg' => ['image/jpeg'], 'jpeg' => ['image/jpeg'], 'png' => ['image/png'],
        'doc' => ['application/msword', 'application/x-ole-storage', 'application/CDFV2'],
        'xls' => ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/CDFV2'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
    ];
    if (in_array($ext, ['docx', 'xlsx'], true) && $mime === 'application/zip') {
        $zip = new \ZipArchive();
        if ($zip->open($file['tmp_name']) !== true) {
            throw new InvalidSubmission('invalid office archive');
        }
        try {
            $entry = $ext === 'docx' ? 'word/document.xml' : 'xl/workbook.xml';
            if ($zip->numFiles > 1000 || $zip->locateName('[Content_Types].xml') === false || $zip->locateName($entry) === false) {
                throw new InvalidSubmission('invalid office content');
            }
        } finally {
            $zip->close();
        }
        $mime = $types[$ext][0];
    }
    if (!isset($types[$ext]) || !in_array($mime, $types[$ext], true)) {
        throw new InvalidSubmission('invalid upload type');
    }
    // Never retain or reuse a client-supplied filename as a filesystem path.
    return ['path' => $file['tmp_name'], 'name' => 'attachment.' . $ext, 'mime' => $mime];
}
