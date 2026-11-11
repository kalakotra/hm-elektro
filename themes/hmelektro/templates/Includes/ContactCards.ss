<%-- Contact data from the settings (scope: SiteConfig). Used next to form blocks. --%>
<ul class="contact-cards list-unstyled">
    <% if $Phone %>
    <li><a class="contact-card" href="$PhoneLink"><span class="contact-card__icon"><% include Icon Name='phone' %></span><span><span class="contact-card__label"><%t App.Phone 'Telefon' %></span><span class="contact-card__value">$Phone</span></span></a></li>
    <% end_if %>
    <% if $Email %>
    <li><a class="contact-card" href="mailto:$Email"><span class="contact-card__icon"><% include Icon Name='mail' %></span><span><span class="contact-card__label"><%t App.Email 'E-Mail' %></span><span class="contact-card__value">$Email</span></span></a></li>
    <% end_if %>
    <% if $OpeningHoursGrouped %>
    <li><div class="contact-card"><span class="contact-card__icon"><% include Icon Name='clock' %></span><span><span class="contact-card__label"><%t App.OfficeHours 'Bürozeiten' %></span><span class="contact-card__value"><% loop $OpeningHoursGrouped %><% if not $IsFirst %><br><% end_if %>$Label $Times<% end_loop %></span></span></div></li>
    <% end_if %>
    <% if $HasAddress %>
    <li><div class="contact-card"><span class="contact-card__icon"><% include Icon Name='map' %></span><span><span class="contact-card__label"><%t App.Address 'Adresse' %></span><span class="contact-card__value">$Street, $PostalCode $City</span></span></div></li>
    <% else_if $ServiceAreaShort %>
    <li><div class="contact-card"><span class="contact-card__icon"><% include Icon Name='map' %></span><span><span class="contact-card__label"><%t App.ServiceArea 'Einsatzgebiet' %></span><span class="contact-card__value">$ServiceAreaShort</span></span></div></li>
    <% end_if %>
</ul>
