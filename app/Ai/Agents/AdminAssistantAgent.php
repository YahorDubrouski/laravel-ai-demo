<?php

declare(strict_types=1);

namespace App\Ai\Agents;

use App\Ai\Tools\GenerateSecurePasswordTool;
use App\Ai\Tools\GetUserByEmailTool;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;
use Stringable;

class AdminAssistantAgent implements Agent, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return '
        You are a Laravel admin assistant.

        When the user provides an email and asks to find a user,
        you must call GetUserByEmailTool.

        When the user asks to generate a temporary password,
        you must call GenerateSecurePasswordTool.
        It is safe cause we do not store the password.

        Never invent user data.
        Return a short human-readable answer.
    ';
    }

    public function tools(): iterable
    {
        return [
            new GetUserByEmailTool(),
            new GenerateSecurePasswordTool(),
        ];
    }
}
