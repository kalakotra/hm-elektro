<div class="hero hero--$Variant<% if $Image.exists %> hero--has-image<% end_if %>">
    <div class="row g-4 g-lg-5 align-items-center">
        <div class="<% if $Image.exists %>col-lg-7<% else %>col-lg-9<% end_if %> hero__body">
            <% if $ShowBreadcrumbs && $Variant != 'home' %>$Page.Breadcrumbs<% end_if %>
            <% if $Eyebrow %><p class="eyebrow">$Eyebrow</p><% end_if %>
            <% if $Title && $ShowTitle %><$HeadingTag class="hero__title">$Title<% if $TitleAccent %> <span class="hero__accent">$TitleAccent</span><% end_if %></$HeadingTag><% end_if %>
            <% if $Lead %><p class="hero__lead lead">$Lead</p><% end_if %>
            <% include CtaButtons Tone='light', Class='mt-4' %>
            <% if $Variant == 'home' && $TrustItemList %>
            <ul class="hero__trust list-unstyled">
                <% loop $TrustItemList %>
                <li><% include Icon Name='check', Class='hero__trust-icon' %>$Text</li>
                <% end_loop %>
            </ul>
            <% end_if %>
        </div>
        <% if $Image.exists %>
        <div class="col-lg-5 hero__media">
            <% if $Variant == 'home' %>
                <% with $Image.FillMax(960,1120) %>
                <img class="hero__image" src="$URL" width="$Width" height="$Height" alt="$Up.ImageAltText.ATT"<% if $Up.IsFirstElement %> fetchpriority="high"<% else %> loading="lazy"<% end_if %> decoding="async">
                <% end_with %>
            <% else %>
                <% with $Image.FillMax(960,700) %>
                <img class="hero__image" src="$URL" width="$Width" height="$Height" alt="$Up.ImageAltText.ATT"<% if $Up.IsFirstElement %> fetchpriority="high"<% else %> loading="lazy"<% end_if %> decoding="async">
                <% end_with %>
            <% end_if %>
            <% if $ShowPhoneBadge && $SiteConfig.Phone %>
            <div class="hero-badge" data-bs-theme="dark">
                <span class="hero-badge__icon"><% include Icon Name='power' %></span>
                <span class="hero-badge__text">
                    <% if $PhoneBadgeLabel %><span class="hero-badge__label">$PhoneBadgeLabel</span><% end_if %>
                    <a class="hero-badge__phone" href="$SiteConfig.PhoneLink">$SiteConfig.Phone</a>
                </span>
            </div>
            <% end_if %>
        </div>
        <% end_if %>
    </div>
    <% if $ShowServiceLinks && $ServiceLinkList %>
    <nav class="hero-links" aria-label="<%t App.ServiceQuickLinks 'Schnellauswahl Leistungen' %>">
        <% if $ServiceLinksLabel %><span class="hero-links__label" aria-hidden="true">$ServiceLinksLabel</span><% end_if %>
        <ul class="hero-links__list list-unstyled">
            <% loop $ServiceLinkList %><li><a class="chip" href="$Link">$QuickLinkTitle</a></li><% end_loop %>
        </ul>
    </nav>
    <% end_if %>
</div>
