<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();

            // Check if user exists with this email
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // Update OAuth info if not set
                if (! $existingUser->google_id) {
                    $existingUser->update([
                        'google_id' => $socialUser->getId(),
                        'provider' => 'google',
                        'provider_id' => $socialUser->getId(),
                    ]);
                }

                Auth::login($existingUser);

                return redirect()->intended('/home');
            }

            // Create new user
            $nameParts = explode(' ', $socialUser->getName(), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'google_id' => $socialUser->getId(),
                'provider' => 'google',
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'password' => Hash::make(uniqid()), // Random password
                'email_verified_at' => now(), // Google accounts are pre-verified
                'is_active' => true,
            ]);

            Auth::login($user);

            return redirect('/profile/complete');

        } catch (Exception $e) {
            return redirect('/login')->withErrors([
                'social' => 'Authentication failed. Please try again.',
            ]);
        }
    }

    /**
     * Redirect to LinkedIn OAuth provider.
     */
    public function redirectToLinkedIn()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    /**
     * Handle LinkedIn OAuth callback.
     */
    public function handleLinkedInCallback()
    {
        try {
            $socialUser = Socialite::driver('linkedin')->user();

            // Check if user exists with this email
            $existingUser = User::where('email', $socialUser->getEmail())->first();

            if ($existingUser) {
                // Update OAuth info if not set
                if (! $existingUser->linkedin_id) {
                    $existingUser->update([
                        'linkedin_id' => $socialUser->getId(),
                        'provider' => 'linkedin',
                        'provider_id' => $socialUser->getId(),
                        'linkedin_url' => $socialUser->profileUrl ?? null,
                    ]);
                }

                Auth::login($existingUser);

                return redirect()->intended('/home');
            }

            // Create new user
            $nameParts = explode(' ', $socialUser->getName(), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'linkedin_id' => $socialUser->getId(),
                'provider' => 'linkedin',
                'provider_id' => $socialUser->getId(),
                'linkedin_url' => $socialUser->profileUrl ?? null,
                'avatar' => $socialUser->getAvatar(),
                'password' => Hash::make(uniqid()), // Random password
                'email_verified_at' => now(), // LinkedIn accounts are pre-verified
                'is_active' => true,
            ]);

            Auth::login($user);

            return redirect('/profile/complete');

        } catch (Exception $e) {
            return redirect('/login')->withErrors([
                'social' => 'Authentication failed. Please try again.',
            ]);
        }
    }
}
