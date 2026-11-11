<%-- Mobile only (< 768px): call + enquiry. --%>
<div class="action-bar d-md-none" role="region" aria-label="<%t App.QuickActions 'Schnellzugriff' %>">
    <% if $SiteConfig.Phone %>
    <a href="$SiteConfig.PhoneLink" class="btn btn-outline-dark"><% include Icon Name='phone', Class='icon-sm' %><%t App.Call 'Anrufen' %></a>
    <% end_if %>
    <% if $ContactPage %>
    <a href="$ContactPage.Link" class="btn btn-primary"><% include Icon Name='mail', Class='icon-sm' %><%t App.SendRequest 'Anfrage senden' %></a>
    <% end_if %>
</div>
