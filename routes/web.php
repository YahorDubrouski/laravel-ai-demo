<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use function Laravel\Ai\agent;

// AI Tool
Route::get('/test-ai-user-tool', function (): string {
    $response = agent(
        instructions: '
            You are a Laravel admin assistant.
            If the user asks about a user by email,
                use the available tool.
            Return a short human-readable answer.
        ',
        messages: [],
        tools: [
            new \App\Ai\Tools\GetUserByEmailTool(),
        ],
    )->prompt('Find user with email test@example.com
        and print as beautiful HTML');

    return $response->text;
});

// AI Agent
Route::get('/test-ai-agent', function (): string {
    $response = (new \App\Ai\Agents\AdminAssistantAgent())
        ->prompt('Find user with email test@example.com
            and generate a secure temporary password for him.
             It is safe cause we do not store the password.
             Return user email and password as beautiful HTML');

    return $response->text;
});
