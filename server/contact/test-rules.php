<?php
declare(strict_types=1);

require __DIR__ . '/FormRules.php';

$valid = ['category' => '製品サポート', 'name' => 'テスト', 'email' => 'test@example.invalid', 'message' => "確認用\n本文", 'privacy' => 'on'];
$checks = 0;
$result = InbesContact\submission($valid);
if ($result['message'] !== $valid['message']) {
    throw new RuntimeException('message mismatch');
}
$checks++;
foreach ([
    ['category', 'invalid'], ['name', ''], ['name', "name\r\nInjected"],
    ['email', ['unexpected']], ['email', "test@example.invalid\nBcc: injected"],
    ['privacy', ''], ['website', 'spam'], ['message', str_repeat('x', 10001)],
] as [$key, $value]) {
    $input = $valid;
    $input[$key] = $value;
    try {
        InbesContact\submission($input);
        throw new RuntimeException('expected rejection');
    } catch (InbesContact\InvalidSubmission $error) {
        $checks++;
    }
}
if (InbesContact\attachment([]) !== null || InbesContact\attachment(['attachment' => ['error' => UPLOAD_ERR_NO_FILE]]) !== null) {
    throw new RuntimeException('empty attachment mismatch');
}
$checks += 2;
foreach ([['other' => ['error' => UPLOAD_ERR_OK]], ['attachment' => ['error' => [0]]], ['attachment' => ['error' => UPLOAD_ERR_INI_SIZE]]] as $input) {
    try {
        InbesContact\attachment($input);
        throw new RuntimeException('expected upload rejection');
    } catch (InbesContact\InvalidSubmission $error) {
        $checks++;
    }
}
echo json_encode(['checks' => $checks, 'passed' => true], JSON_THROW_ON_ERROR) . "\n";
