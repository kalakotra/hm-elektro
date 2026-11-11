<footer class="site-footer" data-bs-theme="dark">
    <div class="container">
        <div class="row g-4 g-lg-5 site-footer__main">
            <% with $SiteConfig %>
            <div class="col-sm-6 col-lg-4">
                <img src="$resourceURL('themes/hmelektro/images/logo-light.svg')" alt="$Title.ATT" width="198" height="52" class="site-footer__logo" loading="lazy">
                <% if $FooterText %><p class="mt-3 mb-0">$FooterText</p><% end_if %>
                <% if $FooterSeal.exists %>
                    <% if $FooterSealLink.exists %><a href="$FooterSealLink.URL" class="d-inline-block mt-3"<% if $FooterSealLink.OpenInNew %> target="_blank" rel="noopener"<% end_if %>><% end_if %>
                    <% with $FooterSeal.ScaleMaxWidth(160) %><img src="$URL" width="$Width" height="$Height" alt="$Up.FooterSeal.Title.ATT" loading="lazy" class="site-footer__seal"><% end_with %>
                    <% if $FooterSealLink.exists %></a><% end_if %>
                <% end_if %>
            </div>
            <% end_with %>
            <% if $ServicePages %>
            <div class="col-sm-6 col-lg-3">
                <h2 class="site-footer__title"><% if $ServiceHolder %><a href="$ServiceHolder.Link">$ServiceHolder.MenuTitle</a><% else %><%t App.Services 'Leistungen' %><% end_if %></h2>
                <ul class="list-unstyled site-footer__links">
                    <% loop $ServicePages %><li><a href="$Link"<% if $isCurrent %> aria-current="page"<% end_if %>>$MenuTitle</a></li><% end_loop %>
                </ul>
            </div>
            <% end_if %>
            <div class="col-sm-6 col-lg-2">
                <h2 class="site-footer__title"><%t App.Company 'Unternehmen' %></h2>
                <ul class="list-unstyled site-footer__links">
                    <% loop $Menu(1) %><% if $ClassName.ShortName != 'ServiceHolderPage' %><li><a href="$Link"<% if $isCurrent %> aria-current="page"<% end_if %>>$MenuTitle</a></li><% end_if %><% end_loop %>
                    <% with $Page('impressum') %><li><a href="$Link"<% if $isCurrent %> aria-current="page"<% end_if %>>$MenuTitle</a></li><% end_with %>
                    <% with $Page('datenschutz') %><li><a href="$Link"<% if $isCurrent %> aria-current="page"<% end_if %>>$MenuTitle</a></li><% end_with %>
                </ul>
            </div>
            <% with $SiteConfig %>
            <div class="col-sm-6 col-lg-3">
                <h2 class="site-footer__title"><%t App.Contact 'Kontakt' %></h2>
                <address class="mb-3">
                    <% if $SchemaOrganisationLegalName %>$SchemaOrganisationLegalName<% else %>$Title<% end_if %>
                    <% if $HasAddress %><br>$Street<br>$PostalCode $City<% else_if $City %><br>$City<% end_if %>
                </address>
                <ul class="list-unstyled site-footer__contact">
                    <% if $Phone %><li><a href="$PhoneLink"><% include Icon Name='phone', Class='icon-sm' %>$Phone</a></li><% end_if %>
                    <% if $Email %><li><a href="mailto:$Email"><% include Icon Name='mail', Class='icon-sm' %>$Email</a></li><% end_if %>
                    <% if $OpeningHoursGrouped %><li><% include Icon Name='clock', Class='icon-sm' %><% loop $OpeningHoursGrouped %><% if not $IsFirst %> · <% end_if %>$Label $Times<% end_loop %></li><% end_if %>
                    <% if $RouteURL %><li><a href="$RouteURL" target="_blank" rel="noopener"><% include Icon Name='map', Class='icon-sm' %><%t App.PlanRoute 'Route planen' %></a></li><% end_if %>
                </ul>
            </div>
            <% end_with %>
        </div>
        <div class="site-footer__bottom d-flex flex-wrap justify-content-between gap-3">
            <p class="mb-0">&copy; $Now.Year <% if $SiteConfig.SchemaOrganisationLegalName %>$SiteConfig.SchemaOrganisationLegalName<% else %>$SiteConfig.Title<% end_if %></p>
            <% if $SiteConfig.Tagline %><p class="mb-0">$SiteConfig.Tagline</p><% end_if %>
        </div>
    </div>
</footer>
