<?php

use Config\Services;
use App\Models\LanguageModel;
use App\Models\UserModel;

/**
 * Language helper — auto-loaded on every request via Config/Autoload.
 * Sets the CI4 locale from the session, or resolves it from the DB on first visit.
 */

// Only run once per request
if (! function_exists('_gh_lang_init_done')) {
    function _gh_lang_init_done(): bool { return true; }

    // If user already has a language in session (set by Language::changeLanguage or a prior request),
    // just apply the locale and we're done.
    if (session()->get('site_lang')) {
        Services::language()->setLocale(session()->get('site_lang'));
    } else {
        // Resolve language: logged-in user preference → DB default → 'en' fallback
        $selectedLanguage = null;

        if (session()->get('login_type')) {
            $userModel = new UserModel();
            $user = null;

            if (session()->get('login_type') == 'email') {
                $user = $userModel->where('email', session()->get('email'))
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->first();
            }

            if (session()->get('login_type') == 'mobile') {
                $user = $userModel->where('mobile', session()->get('mobile'))
                                 ->where('is_active', 1)
                                 ->where('is_delete', 0)
                                 ->first();
            }

            if ($user && !empty($user['language'])) {
                $languageModel = new LanguageModel();
                $selectedLanguage = $languageModel->where('lang_short', $user['language'])->first();
            }
        }

        if (!$selectedLanguage) {
            $languageModel = new LanguageModel();
            $selectedLanguage = $languageModel->where('is_default', 1)->first();
        }

        if ($selectedLanguage) {
            session()->set('site_lang', $selectedLanguage['lang_short']);
            session()->set('is_rtl', $selectedLanguage['is_rtl']);
            Services::language()->setLocale($selectedLanguage['lang_short']);
        } else {
            session()->set('site_lang', 'en');
            session()->set('is_rtl', 0);
            Services::language()->setLocale('en');
        }
    }
}
