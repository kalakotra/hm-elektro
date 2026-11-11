<% with $SiteConfig %>
<div class="topbar d-none d-lg-block" role="region" aria-label="<%t App.QuickInfo 'Einsatzgebiet, Bürozeiten und E-Mail' %>">
    <div class="container d-flex justify-content-between align-items-center gap-3">
        <ul class="topbar__info list-unstyled d-flex flex-wrap gap-4 mb-0">
            <% if $ServiceAreaShort %><li><% include Icon Name='map', Class='icon-sm' %><%t App.ServiceAreaPrefix 'Einsatzgebiet:' %> $ServiceAreaShort</li><% end_if %>
            <% if $OpeningHoursGrouped %><li><% include Icon Name='clock', Class='icon-sm' %><% loop $OpeningHoursGrouped %><% if not $IsFirst %> · <% end_if %>$Label $Times<% end_loop %></li><% end_if %>
        </ul>
        <% if $Email %><a class="topbar__link" href="mailto:$Email"><% include Icon Name='mail', Class='icon-sm' %>$Email</a><% end_if %>
    </div>
</div>
<% end_with %>
<header class="site-header" data-sticky-header>
    <nav class="navbar navbar-expand-lg" aria-label="<%t App.MainNav 'Hauptnavigation' %>">
        <div class="container">
            <a class="navbar-brand" href="$BaseHref" rel="home">
                <img src="$resourceURL('themes/hmelektro/images/logo.svg')" alt="$SiteConfig.Title.ATT – <%t App.Home 'Startseite' %>" width="198" height="52" class="site-header__logo">
            </a>
            <button class="btn btn-outline-dark btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#site-nav" aria-controls="site-nav" aria-label="<%t App.OpenMenu 'Menü öffnen' %>">
                <% include Icon Name='menu' %>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="site-nav" aria-labelledby="site-nav-label">
                <div class="offcanvas-header">
                    <p class="offcanvas-title h5 mb-0" id="site-nav-label"><%t App.Menu 'Menü' %></p>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<%t App.CloseMenu 'Menü schließen' %>"></button>
                </div>
                <div class="offcanvas-body align-items-lg-center">
                    <% include Navigation %>
                    <div class="site-header__actions d-flex flex-column flex-lg-row align-items-lg-center gap-3 ms-lg-auto mt-4 mt-lg-0">
                        <% if $SiteConfig.Phone %>
                        <a class="site-header__phone" href="$SiteConfig.PhoneLink">
                            <span class="site-header__phone-label"><%t App.CallDirect 'Direkt anrufen' %></span>
                            <span class="site-header__phone-number">$SiteConfig.Phone</span>
                        </a>
                        <% end_if %>
                        <% if $ContactPage %><a href="$ContactPage.Link" class="btn btn-primary"><%t App.RequestQuote 'Angebot anfragen' %></a><% end_if %>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
