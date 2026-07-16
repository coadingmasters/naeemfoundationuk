{{-- Cookie consent banner + preferences. The choice is persisted in localStorage. --}}
<div class="nf-cookie" data-cookie hidden>
    <div class="nf-cookie__card" role="dialog" aria-label="Cookie consent" aria-live="polite">

        {{-- ===== Main ===== --}}
        <div data-cookie-view="main">
            <div class="flex items-start gap-3">
                <span class="nf-cookie__icon" aria-hidden="true">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                        <path d="M12 3a9 9 0 1 0 9 9 3 3 0 0 1-3-3 3 3 0 0 1-3-3 3 3 0 0 1-3 3z" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="9" cy="12" r="1" fill="currentColor" stroke="none"/>
                        <circle cx="13" cy="15" r="1" fill="currentColor" stroke="none"/>
                        <circle cx="15" cy="11" r="1" fill="currentColor" stroke="none"/>
                    </svg>
                </span>
                <div>
                    <h2 class="nf-cookie__title">We value your privacy</h2>
                    <p class="nf-cookie__text">
                        We use cookies to enhance your browsing experience, analyse site traffic and personalise content.
                        By clicking “Accept All”, you consent to our use of cookies. Read our
                        <a href="{{ route('privacy-policy') }}" class="nf-cookie__link">Privacy&nbsp;Policy</a>.
                    </p>
                </div>
            </div>

            <div class="nf-cookie__actions">
                <button type="button" class="nf-cookie__btn nf-cookie__btn--primary" data-cookie-accept>Accept All</button>
                <button type="button" class="nf-cookie__btn nf-cookie__btn--ghost" data-cookie-reject>Decline</button>
                <button type="button" class="nf-cookie__btn nf-cookie__btn--text" data-cookie-prefs>Preferences</button>
            </div>
        </div>

        {{-- ===== Preferences ===== --}}
        <div data-cookie-view="settings" hidden>
            <h2 class="nf-cookie__title">Manage cookie preferences</h2>
            <p class="nf-cookie__text">Choose which cookies you allow. Necessary cookies are always on.</p>

            <ul class="nf-cookie__list">
                <li class="nf-cookie__cat">
                    <div class="min-w-0">
                        <p class="nf-cookie__cat-title">Strictly necessary</p>
                        <p class="nf-cookie__cat-desc">Required for the site to work. Always active.</p>
                    </div>
                    <label class="nf-switch"><input type="checkbox" checked disabled><span></span></label>
                </li>
                <li class="nf-cookie__cat">
                    <div class="min-w-0">
                        <p class="nf-cookie__cat-title">Analytics</p>
                        <p class="nf-cookie__cat-desc">Help us understand how visitors use the site.</p>
                    </div>
                    <label class="nf-switch"><input type="checkbox" data-cookie-cat="analytics"><span></span></label>
                </li>
                <li class="nf-cookie__cat">
                    <div class="min-w-0">
                        <p class="nf-cookie__cat-title">Marketing</p>
                        <p class="nf-cookie__cat-desc">Used to show you relevant campaigns and appeals.</p>
                    </div>
                    <label class="nf-switch"><input type="checkbox" data-cookie-cat="marketing"><span></span></label>
                </li>
            </ul>

            <div class="nf-cookie__actions">
                <button type="button" class="nf-cookie__btn nf-cookie__btn--primary" data-cookie-save>Save preferences</button>
                <button type="button" class="nf-cookie__btn nf-cookie__btn--ghost" data-cookie-back>Back</button>
            </div>
        </div>
    </div>
</div>
