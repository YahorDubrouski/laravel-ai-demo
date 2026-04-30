<?php

declare(strict_types=1);

namespace App\Ai\Tools;

use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

final class GetUserByEmailTool implements Tool
{
    public function description(): Stringable|string
    {
        return 'Find a user by email address
            and return safe public user data.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'email' => $schema
                ->string()
                ->description('User email address')
                ->required(),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        $email = $request->string('email')->toString();

        $user = User::query()
            ->select(['id', 'name', 'email', 'created_at'])
            ->where('email', $email)
            ->first();

        if (! $user) {
            return json_encode([
                'found' => false,
                'message' => 'User not found.',
            ], JSON_THROW_ON_ERROR);
        }

        return json_encode([
            'found' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at?->toDateTimeString(),
            ],
        ], JSON_THROW_ON_ERROR);
    }
}
