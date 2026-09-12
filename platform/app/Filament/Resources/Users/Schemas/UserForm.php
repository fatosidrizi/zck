<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->options(UserRole::class)
                    ->default(UserRole::User)
                    ->required()
                    // Nobody demotes themselves by accident and locks the office out of
                    // account management. Another super admin has to do it.
                    ->disabled(fn (?User $record) => $record !== null && $record->is(auth()->user()))
                    ->dehydrated(fn (?User $record) => $record === null || ! $record->is(auth()->user()))
                    ->helperText('Editors publish content. Admins also review NGO applications and handle discrimination reports. Super admins also manage accounts.'),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    // Required to create an account; on edit, blank means "keep the current one".
                    ->required(fn (string $operation) => $operation === 'create')
                    ->dehydrated(fn (?string $state) => filled($state))
                    ->helperText(fn (string $operation) => $operation === 'edit' ? 'Leave blank to keep the current password.' : null),
            ]);
    }
}
