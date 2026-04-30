<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Str;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class GenerateSecurePasswordTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Generate a secure temporary password.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'length' => $schema
                ->integer()
                ->description('Password length. Minimum 12, maximum 32.')
                ->required(),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        $length = max(12, min(32, $request->integer('length')));

        return json_encode([
            'password' => Str::password(length: $length),
        ], JSON_THROW_ON_ERROR);
    }
}
